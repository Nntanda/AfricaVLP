<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\Core\Configure;

/**
 * CloudUpload component
 */
class CloudUploadComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function upload($file, $options)
    {
        \Cloudinary::config(Configure::read('Cloudinary'));
        $linkpath = 'uploads'. '/' .$options['path'];
        
        try {
            if ($options['resize'] === true) {
                $image_height = $options['resize_h']; //max image height
                $image_width = $options['resize_w']; //max image width

                $responce = \Cloudinary\Uploader::upload($file, [
                    'folder' => $linkpath,
                    'use_filename' => true,
                    'resource_type' => 'auto',
                    'eager' => [
                        "width" => $image_width, "height" => $image_height, "crop" => "pad",
                    ]
                ]);
            } else {
                $responce = \Cloudinary\Uploader::upload($file, [
                    'folder' => $linkpath,
                    'use_filename' => true,
                    'resource_type' => 'auto'
                ]);
            }

            $uploaded = ['url' => ''];
            if(isset($responce['eager']) && !empty($responce['eager'])) {
                $uploaded['url'] = $responce['eager'][0]['secure_url'];
            } else {
                $uploaded['url'] = $responce['secure_url'];
            }
            
            return $uploaded;
        } catch (Exception $ex) {
            return FALSE;
        }
    }
}
