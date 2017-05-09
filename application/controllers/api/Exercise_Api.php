<?php

require FCPATH . 'application/libraries/REST_Controller.php';
require_once FCPATH . 'application/entity/autoload.php';

class Exercise_Api extends REST_Controller
{

    /**
     * @var Doctrine
     */
    protected $dt;

    protected $em;

    public function __construct()
    {
        parent::__construct();

        $this->dt = $this->doctrine;
        $this->em = $this->dt->em;

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['exercises_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['exercises_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['exercises_delete']['limit'] = 50; // 50 requests per hour per user/key
    }

    public function index_get()
    {
        $id = $this->get('id');

        $response = [];
        if (!isset($id)) {
            $exercises = $this->em->getRepository(EXERCISE)->findAll();
            $response['exerciseList'] = convertToResponseArray($exercises);
            $this->set_response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        } else {
            /**
             * @var Entity\Exercise $exercise
             */

            $exercise = $this->em->getRepository(EXERCISE)->find($id);
            $response['exerciseList'] = convertToResponseArray([$exercise]);
            $this->set_response($response, REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
        }

    }

    public function index_post()
    {
        $name = $this->post('name');
        $type = $this->post('type');

        $message = [
            'name' => $name,
            'type' => $type
        ];
        $exercise = new \Entity\Exercise();
        $exercise->setName($name);
        $exercise->setType($type);
        $this->em->persist($exercise);
        $this->em->flush();

        $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
    }
}
