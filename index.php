<?php

// This is my controller

// Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// Require autoload file
require_once('vendor/autoload.php');
require_once('model/data-layer.php');
require_once('model/validate.php');

// Instantiate F3 Base Class (:: = static method || -> = instance method)
$f3 = Base::instance();

// Define a default route (328/diner)
$f3->route('GET /', function() {
    // Instantiate a view
    $view = new Template();
    echo $view->render('views/diner-home.html');
});

// Define a breakfast route (328/diner/breakfast)
$f3->route('GET /breakfast', function() {
    // Instantiate a view
    $view = new Template();
    echo $view->render('views/breakfast.html');
});

// Define a lunch route + page (328/diner/lunch)
$f3->route('GET /lunch', function() {
    // Instantiate a view
    $view = new Template();
    echo $view->render('views/lunch.html');
});

// Define an order 1 route + page (328/diner/order1)
$f3->route('GET|POST /order1', function($f3) {

    // If the form has been posted
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        // Validate the food
        if (validFood(trim($_POST['food']))) {
            // Move data from POST array to SESSION array
            $_SESSION['food'] = trim($_POST['food']);

        } else {
            $f3->set('errors["food"]', 'Food must have at least two characters');
        }

        // Validate the meal
        if (validMeal($_POST['meal'])) {
            $_SESSION['meal'] = $_POST['meal'];
        } else {
            $f3->set('errors["meal"]', 'Meal is invalid');
        }

        // Check for errors
        if (empty($f3->get('errors'))) {
            $f3->reroute('order2');
        }
    }

    // Add meals to f3 hive
    $f3->set('meals', getMeals());

    // Instantiate a view
    $view = new Template();
    echo $view->render('views/order1.html');
});

// Define an order 2 route + page (328/diner/order2)
$f3->route('GET|POST /order2', function ($f3) {
    // If the form has been posted
    /*if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Move data from Post to SESSION array

        // Redirect to summary page
    }*/

    // Add condiments to f3 hive
    $f3->set('condiments', getCondiments());

    // Instantiate a view
    $view = new Template();
    echo $view->render('views/order2.html');

});

// Define a summary route + page (328/diner/summary)
$f3->route('GET /summary', function() {
    $view = new Template();
    echo $view->render('views/summary.html');
});

// Run Fat Free
$f3->run();