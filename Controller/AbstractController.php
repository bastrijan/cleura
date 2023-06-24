<?php

namespace Src\Controller;

use Src\TableGateways\ForumGateway;
use Src\Interfaces\HttpResponseConstantsInterface;

abstract class AbstractController {
      abstract protected function _initGateway();
      abstract protected function _validate($input, $requestMethod);
      abstract protected function _authenticate();

      protected $_requestMethod;
      protected $_identifier;
      protected $_Gateway;
      protected $_filter;

      public function __construct($requestMethod, $identifier, $filter) {
            $this->_requestMethod = $requestMethod;
            $this->_identifier = $identifier;
            $this->_filter = $filter;

            $this->_initGateway();
      }

      public function processRequest() {

            switch ($this->_requestMethod) {
                  case 'GET':
                        if($this->_filter) {
                              $response = $this->_getAll($this->_filter, $this->_identifier);
                        }
                        elseif ($this->_identifier) {
                              $response = $this->_getSingle($this->_identifier);
                        }
                        else {
                              $response = $this->_getAll();
                        };
                        break;
                  case 'POST':
                        $response = $this->_createFromRequest();
                        break;
                  case 'PUT':
                  case 'PATCH':
                        $response = $this->_updateFromRequest($this->_identifier);
                        break;
                  case 'DELETE':
                        $response = $this->_deleteSingle($this->_identifier);
                        break;
                  default:
                        // Unsupported request method
                        $response = $this->_notFoundResponse();
                  break;
            }
            header($response['status_code_header']);
            if ($response['body']) {
                  echo $response['body'];
            }
      }

      protected function _getAll($filter = null, $identifier = null) {
            $result = $this->_Gateway->findAll($filter, $identifier);
            $response['status_code_header'] = HttpResponseConstantsInterface::HTTP_200;
            $response['body'] = json_encode($result);
            return $response;
      }

      protected function _getSingle($identifier) {
            $result = $this->_Gateway->find($identifier);
            if (!$result) {
                  return $this->_notFoundResponse();
            }
            $response['status_code_header'] = HttpResponseConstantsInterface::HTTP_200;
            $response['body'] = json_encode($result);
            return $response;
      }

      protected function _createFromRequest() {
            if(!$this->_authenticate()) {
                  return $this->_notUnauthorized();
            }
            
            $input = (array) json_decode(file_get_contents("php://input"), TRUE);
            if (!$this->_validate($input, $this->_requestMethod)) {
                  return $this->_unprocessableEntityResponse();
            }
            $this->_Gateway->insert($input);
            $response['status_code_header'] = HttpResponseConstantsInterface::HTTP_201;
            $response['body'] = null;
            return $response;
      }

      protected function _updateFromRequest($identifier) {
            if(!$this->_authenticate()) {
                  return $this->_notUnauthorized();
            }

            $result = $this->_Gateway->find($identifier);
            if (!$result) {
                  return $this->_notFoundResponse();
            }
            $input = (array) json_decode(file_get_contents("php://input"), TRUE);
            if (!$this->_validate($input, $this->_requestMethod)) {
                  return $this->_unprocessableEntityResponse();
            }
            $ret = $this->_Gateway->update($identifier, $input);
            $response['status_code_header'] = HttpResponseConstantsInterface::HTTP_200;
            $response['body'] = null;
            return $response;
      }

      protected function _deleteSingle($identifier) {
            if(!$this->_authenticate()) {
                  return $this->_notUnauthorized();
            }

            $result = $this->_Gateway->find($identifier);
            if (!$result) {
                  return $this->_notFoundResponse();
            }

            $this->_Gateway->deleteSingle($identifier);
            $response['status_code_header'] = HttpResponseConstantsInterface::HTTP_200;
            $response['body'] = null;
            return $response;
      }

      protected function _unprocessableEntityResponse() {
            $response['status_code_header'] = HttpResponseConstantsInterface::HTTP_422;
            $response['body'] = 
                  json_encode([
                        'error' => 'Invalid input'
                  ]);
            return $response;
      }

      protected function _notFoundResponse() {
            $response['status_code_header'] = HttpResponseConstantsInterface::HTTP_404;
            $response['body'] = null;
            return $response;
      }  

      protected function _notUnauthorized() {
            $response['status_code_header'] = HttpResponseConstantsInterface::HTTP_401;
            $response['body'] = 
                  json_encode([
                        'error' => 'Unauthorized access'
                  ]);
            return $response;
      }
}
