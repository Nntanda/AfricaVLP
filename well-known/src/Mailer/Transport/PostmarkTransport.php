<?php
/**
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 *
 * @author        Ian den Hartog (https://iandh.nl)
 * @link          https://iandh.nl
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Mailer\Transport;

use Cake\Http\Client;
use Cake\Mailer\AbstractTransport;
use Cake\Mailer\Email;
use Cake\Network\Exception\SocketException;

/**
 * Send mail using SendGrid
 */
class PostmarkTransport extends AbstractTransport
{
    /**
     * Http client
     *
     * @var \Cake\Network\Http\Client
     */
    private $http;

    /**
     * Transport config for this class
     *
     * @var array
     */
    protected $_defaultConfig = [
        'api_key' => null,
    ];

    /**
     * Send mail
     *
     * @param \Cake\Mailer\Email $email Email instance.
     * @return array
     */
    public function send(Email $email)
    {
        $message = [
            'HtmlBody' => $email->message(Email::MESSAGE_HTML),
            'TextBody' => $email->message(Email::MESSAGE_TEXT),
            'Subject' => mb_decode_mimeheader($email->getSubject()), // Decode because SendGrid is encoding
            'From' => current($email->getFrom()). " <". key($email->getFrom()). ">",
            'To' => "",
            'Cc' => "",
            'Bcc' => "",
            'ReplyTo' => $email->getReplyTo() ? key($email->getReplyTo()) : key($email->getFrom()),
        ];
        // Add recipients
        $recipients = [
            'To' => $email->getTo(),
            'Cc' => $email->getCc(),
            'Bcc' => $email->getBcc()
        ];
        foreach ($recipients as $type => $emails) {
            foreach ($emails as $mail => $name) {
                $message[$type] = $message[$type]. $mail .", ";
            }
        }

        // Create a new scoped Http Client
        $this->http = new Client([
            'host' => 'api.postmarkapp.com',
            'scheme' => 'https',
            'headers' => [
                'User-Agent' => 'CakePHP Postmark Plugin',
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ]
        ]);

        $message = $this->_attachments($email, $message);

        return $this->_send(json_encode($message));
    }

    /**
     * Send normal email
     *
     * @param  array $message The Message Array
     * @return array Returns an array with the results from the SendGrid API
     * @throws SocketException
     */
    protected function _send($message)
    {
        $options = [
            'headers' => ['X-Postmark-Server-Token' => $this->getConfig('api_key')]
        ];
        $response = $this->http->post('/email', $message, $options);
        if ($response->getStatusCode() !== 200) {
            throw new SocketException(sprintf(
                'Postmark error %s %s: %s',
                $response->getStatusCode(),
                $response->getReasonPhrase(),
                $response->getJson()['ErrorCode']. ' - ' .$response->getJson()['Message']
            ));
        }

        return $response->getJson();
    }

    /**
     * Format the attachments
     *
     * @param \Cake\Mailer\Email $email Email instance.
     * @param array $message A message array.
     * @return array Message
     */
    protected function _attachments(Email $email, array $message = [])
    {
        $i = 0;
        foreach ($email->getAttachments() as $filename => $attach) {
            $content = isset($attach['data']) ? base64_decode($attach['data']) : file_get_contents($attach['file']);

            $message['Attachments'][$i]['Name'] = $filename;
            $message['Attachments'][$i]['Content'] = $content;
            $message['Attachments'][$i]['ContentType'] = $attach['mimetype'];
            if (isset($attach['contentId'])) {
                $message['content'][$i]['ContentId'] = "cid:" . $attach['contentId'];
            }
            $i++;
        }

        return $message;
    }
}
