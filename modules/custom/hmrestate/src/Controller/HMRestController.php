<?php

namespace Drupal\hmrestate\Controller;

use Drupal\Core\Cache\CacheableJsonResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Drupal\Core\Access\CsrfTokenGenerator;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
* Class HMRestController.
*/

class HMRestController extends ControllerBase {

    const REDIRECTS = [
        'admin' => '/admin/content',
        'vendor' => '/vendor',
        'buyer' => '/buyer',
        'moderator' => '/moderator/vendor'
    ];

    public function register() {
        try{
            $validRoles = ['vendor','buyer'];
            if(!\in_array($_POST['role'],$validRoles)){
                throw new \Exception("Invalid");
            }

            $values = array(
                'field_first_name' => $_POST['first_name'],
                'field_last_name' => $_POST['last_name'],
                'field_phone' => $_POST['phone'],
                'name' => $_POST['role'].'-'.$_POST['email'],
                'mail' => $_POST['email'],
                'roles' => [$_POST['role']],
                'pass' => $_POST['password'],
                'status' => 1,
            );
            $account = entity_create('user', $values);
            $account->save();


            user_login_finalize($account);

            $message = ["status" => "success","message" => "Registered successfully!","redirect" => self::REDIRECTS[$_POST['role']]];
            return new JsonResponse($message,200);
        } catch(\Exception $e){
            $error = 'An error has occured. Please try again later';
            if(strpos($e->getMessage(),'Duplicate') !== false){
                $error = 'Email already exists.';
            }
            $message = ["status" => "failed","message"=>$error];
            return new JsonResponse($message,400);
        }
    }


    public function login() {
        $username = $_POST['role'].'-'.$_POST['email'];
        $password = $_POST['password'];
      
        try{    
            $uid = \Drupal::service('user.auth')->authenticate($username, $password);        
            if(!$uid){
                $uid = \Drupal::service('user.auth')->authenticate($_POST['email'], $password);
            }
            if(!$uid){
                $_POST['role'] = 'moderator';
                $uid = \Drupal::service('user.auth')->authenticate('moderator-'.$_POST['email'], $password);
            }
            if(!$uid){
                $_POST['role'] = 'admin';
                $uid = \Drupal::service('user.auth')->authenticate('administrator-'.$_POST['email'], $password);
            }

            if($uid)
            {
                $user = User::load($uid);
                user_login_finalize($user);

                $message = ["status" => "success","message" => "Login successfully!","redirect" => self::REDIRECTS[$_POST['role']]];
                return new JsonResponse($message,200);
            }
            else
            {
                throw new \Exception("Wrong Username/Password");
            }
        } catch(\Exception $e){
            $error = 'Wrong Username/Password';
            $message = ["status" => "failed","message"=>$error];
            return new JsonResponse($message,400);
        }
    }

    public function contact()
    {

        $message = ["status" => "success","message" => "Message Sent Successfully!"];
        return new JsonResponse($message,200);


        $to = '';
        $subject = 'Contact Us Submission';
        $from = '';
        $message = 'Email: '.$_POST['email'].'<br/>';
        $message .= 'Phone: '.$_POST['phone'].'<br/>';
        $message .= 'Message: '.$_POST['message'];
        simple_mail_send($from, $to, $subject, $message);
    }

}