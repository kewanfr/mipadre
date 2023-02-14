<?php
class Router
{

  static $routes = array();
  static $prefixes = array();

  static function prefix($url, $prefix){
    self::$prefixes[$url] = $prefix;
  }

  /**
   * Permet de parser une url
   * @param $url Url à parser
   */
  static function parse($url, $request)
  {
    $url = trim($url, '/');

    if(empty($url)){
      $url = Router::$routes[0]['url'];
    }else{
      $match = false;
      foreach(Router::$routes as $v){
        if(!$match && preg_match($v['redirreg'], $url, $match)){
          $url = $v['origin'];
          foreach($match as $k => $v){
            $url = str_replace(":$k:", $v, $url);
          }
          $match = true;
        }
      }
    }

    $params = explode('/', $url);
    if(in_array($params[0], array_keys(self::$prefixes))){
      $request->prefix = self::$prefixes[$params[0]];
      array_shift($params);      
    }
    
    $request->controller = isset($params[0]) ? $params[0] : 'index';
    $request->controller = $params[0];
    $request->action = isset($params[1]) ? $params[1] : 'index';
    foreach(self::$prefixes as $k => $v){
      if(strpos($request->action, $v.'_') === 0){
        $request->prefix = $v;
        $request->action = str_replace($v.'_', '', $request->action);
      }
    }
    $request->params = array_slice($params, 2);
    return $request;
  }

  /**
   * Permet de connecter une url à un controller
   * @param $url Url à parser
   * @param $route Url à parser
   */
  static function connect($redir, $url)
  {
    $r = array();
    $prefixOrigin = explode('/', $redir)[0];
    $prefix = array_search($prefixOrigin, self::$prefixes);
    if($prefix){
      $redir = str_replace($prefixOrigin, $prefix, $redir);
      $url = str_replace($prefixOrigin, $prefix, $url);
    }

    $r['params'] = array();
    $r['url'] = $url;

    $r['originreg'] = preg_replace('/([a-z0-9]+):([^\/]+)/', '${1}:(?P<${1}>${2})', $url);
    $r['originreg'] = str_replace('/*', '(?P<args>/?.*)', $r['originreg']);
    $r['originreg'] = '/^' . str_replace('/', '\/', $r['originreg']).'$/';

    $r['origin'] = preg_replace('/([a-z0-9]+):([^\/]+)/', ':${1}:', $url);
    $r['origin'] = str_replace('/*', ':args:', $r['origin']);


    $params = explode('/', $url);
    foreach ($params as $k => $v) {
      if (strpos($v, ':')) {
        $p = explode(':', $v);
        $r['params'][$p[0]] = $p[1];
      }
      // else {
      //   if ($k == 0) {
      //     $r['controller'] = $v;
      //   } elseif ($k == 1) {
      //     $r['action'] = $v;
      //   }
      // }
    }

    $r['redirreg'] = $redir;
    $r['redirreg'] = str_replace('/*', '(?P<args>/?.*)', $r['redirreg']);
    foreach ($r['params'] as $k => $v) {
      $r['redirreg'] = str_replace(":$k", "(?P<$k>$v)", $r['redirreg']);
    }
    $r['redirreg'] = '/^' . str_replace('/', '\/', $r['redirreg']).'$/';

    $r['redir'] = preg_replace('/:([a-z0-9]+)/', ':${1}:', $redir);
    $r['redir'] = str_replace('/*', ':args:', $r['redir']);


    self::$routes[] = $r;
  }

  /**
   * Permet de générer une url à partir d'un controller et d'une action
   * @param $url Url à parser
   * @param $route Url à parser
   */
  static function url($url)
  {
    trim($url, '/');
    foreach (self::$routes as $v) {
      if (preg_match($v['originreg'], $url, $match)) {
        $url = $v['redir'];
        foreach ($match as $k => $v) {
          if (is_string($k)) {
            $url = str_replace(":$k", $v, $url);
          }
        }
      }
    }
    foreach(self::$prefixes as $k=>$v){
      if(strpos($url, $v) === 0){
        $url = str_replace($v, $k, $url);
      }
    }
    return str_replace("//", "/", BASE_URL . "/" . $url);
  }

  static function webroot($url){
    trim($url, '/');
    return str_replace("//", "/", BASE_URL . "/" . $url);
  }

}

?>