<?php

/**
* \HomeController
*/

class HomeController extends BaseController
{

  public function home()
  {

    return Twig::render('index.twig', $data);
    
    // logs('hehe','module');//ok
    // var_dump(config('mysql'));exit; // ok
    // var_dump(D('product')->select());exit; // OK
    // dump(M('product')->get());exit; // ok
  }

}