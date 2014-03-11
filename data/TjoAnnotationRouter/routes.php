<?php
 return array (
  'index' => 
  array (
    'child_routes' => 
    array (
      'index' => 
      array (
        'type' => 'literal',
        'options' => 
        array (
          'route' => '',
          'constraints' => 
          array (
          ),
          'defaults' => 
          array (
            'action' => 'index',
            'controller' => 'applicationcontrollerindex',
          ),
        ),
      ),
      'home' => 
      array (
        'type' => 'literal',
        'options' => 
        array (
          'route' => '/home',
          'constraints' => 
          array (
          ),
          'defaults' => 
          array (
            'action' => 'home',
            'controller' => 'applicationcontrollerindex',
          ),
        ),
      ),
    ),
  ),
);
