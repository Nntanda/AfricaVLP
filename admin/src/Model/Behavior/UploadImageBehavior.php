<?php
namespace App\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Utility\Hash;
use Cake\Http\Client;
use Cake\Core\Configure;
use ArrayObject;

/**
 * UploadImage behavior
 */
class UploadImageBehavior extends Behavior
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
    	'fields' => [
    		// 'db_field' => [
			// 	'input_file' => '',
			// 	'resize' => false,
			// 	'resize_w' => '',
			// 	'resize_h' => '',
			// 	'save_type' => false,
			// 	'type_field' => '',
			// ]
		],
		'file_storage' => 'local'
    ];

    /**
     * Initialize hook
     *
     * @param array $config The config for this behavior.
     * @return void
     */
    public function initialize(array $config) {
    	$this->setConfig($config);
    }

    /**
     * [beforeSave description]
     * @param  Event           $event   
     * @param  EntityInterface $entity  
     * @param  ArrayObject     $options 
     * @return void
     */
    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        foreach ($this->getConfig('fields', []) as $field => $uploadData) {
        	// if (in_array($field, $this->protectedFieldNames)) {
        	// 	continue;
			// }

        	if (Hash::get((array)$entity->get($uploadData['input_file']), 'error') !== UPLOAD_ERR_OK) {
        		continue;
        	}

        	$data = $entity->get($uploadData['input_file']);
        	$filename = $data['name'];
        	$tmp_name = $data['tmp_name'];
        	$UploadImageName = $this->uniqueName($filename);
        	$linkpath = 'uploads'. '/' .$this->_table->getTable();
        	$uploadpath = 'img/'.  $linkpath;
        	$basepath = WWW_ROOT . $uploadpath;
        	$url = $uploadpath . '/' . $UploadImageName;
			$url_link = \Cake\Routing\Router::Url('/'. $url, ['_full' => true]);
			// $url_link = $linkpath . '/' . $UploadImageName;
			
			/**
			 * Determine image storage
			 */
			$fileStorage = $this->getConfig('file_storage');
			switch ($fileStorage) {
				case 'local':
					if (!is_dir($basepath)) {
						mkdir($basepath, 0777, true);
					}
		
					if ($uploadData['resize'] === true) {
						$image_height = $uploadData['resize_h']; //max image height
						$image_width = $uploadData['resize_w']; //max image width
		
						//get image info from a valid image file
						$image_info  = getimagesize($tmp_name); 
		
						//get mime type of the image
						if($image_info){
							$image_type	= $image_info['mime']; //image mime
						}else{
							print json_encode(array('type'=>'error', 'msg'=>'Invalid Image file !')); 
							exit;
						}
		
						//ImageMagick
						$image = new \Imagick($tmp_name);
		
						/* At this point you can do many other things with ImageMagick, 
						not just resize. See http://php.net/manual/en/class.imagick.php */
		
						if($image_type=="image/gif"){ //Determine image format, if it's GIF file, resize each frame
							$image = $image->coalesceImages(); 
							foreach ($image as $frame) { 
								$frame->resizeImage( $image_height , $image_width , \Imagick::FILTER_LANCZOS, 1, TRUE);
							} 
							$image = $image->deconstructImages(); 
						}else{
							//otherwise just resize
							$image->resizeImage( $image_height , $image_width , \Imagick::FILTER_LANCZOS, 1, TRUE);
						}
		
						//save image file to destination directory
						$results = $image->writeImages($url, true);
		
					} else {
						if (!move_uploaded_file($tmp_name, $url)) {
							return FALSE;
						}
					}
		
					$entity->set($field, $url_link);
					if ($uploadData['save_type'] === true) $entity->set($uploadData['type_field'], $data['type']);
					break;

				case 'cloudinary':
					$cloudinary_config = Configure::read('Cloudinary');
					\Cloudinary::config($cloudinary_config);
					
					// dd($filename);
					try {
						if ($uploadData['resize'] === true) {
							$image_height = $uploadData['resize_h']; //max image height
							$image_width = $uploadData['resize_w']; //max image width
	
							$responce = \Cloudinary\Uploader::upload($tmp_name, [
								'folder' => $linkpath,
								'use_filename' => true,
								'resource_type' => 'auto',
								'eager' => [
									"width" => $image_width, "height" => $image_height, "crop" => "pad",
								]
							]);
						} else {
							$responce = \Cloudinary\Uploader::upload($tmp_name, [
								'folder' => $linkpath,
								'use_filename' => true,
								'resource_type' => 'auto'
							]);
						}

						if(isset($responce['eager']) && !empty($responce['eager'])) {
							$entity->set($field, $responce['eager'][0]['secure_url']);
						} else {
							$entity->set($field, $responce['secure_url']);
						}
						if ($uploadData['save_type'] === true) $entity->set($uploadData['type_field'], $data['type']);
					} catch (Exception $ex) {
						return FALSE;
					}
					
					break;
				
				default:
					# code...
					break;
			}


        }
    }

    public function uniqueName($filename)
    {
    	$ext = substr(strtolower(strrchr($filename, '.')), 1);
       	// $name = substr(strtolower(strrchr($filename, '.')), 0); substr($filename, 0, strrpos($filename, "."));
       	$name = substr(strtolower($filename), 0, strrpos($filename, "."));

    	return $name.time().'.'.$ext;
	}
	
	
}
