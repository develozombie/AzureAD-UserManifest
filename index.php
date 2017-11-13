<?php

$mfst = new Manifiesto();
$mfst->GenerarManifiesto();

class Manifiesto
{
    public function GenerarManifiesto()
    {
        $result = $this->RolesToArray('admin,observer,auditoria', 'encargado de administrar,encargado de observar,encargado de molestar');
    }
    public function RolesToArray($roles, $descripciones)
    {
        $roles = explode(',', $roles);
        $descripciones = explode(',', $descripciones);

        for ($i=0; $i < count($roles); ++$i) 
        { 
            $role = new Role();
            $role->setNombre($roles[$i]);
            $role->setDescripcion($descripciones[$i]);
            $role->setGuid();

            $result[$i] = $role;
            
        }
        return $result;
    }
}

class Role
{
    var $nombre;
    var $descripcion;
    var $guid;

    function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }
    function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }
    function setGuid()
    {
        $gui = new Guid();
        $this->guid = $gui->GUIDv4();
    }
    function getGuid()
    {
        return $this->guid;
    }
    function getNombre()
    {
        return $this->nombre;
    }
    function getDescripcion()
    {
        return $this->descripcion;
    }

}

class Guid
{
    static function GUIDv4 ($trim = true)
    {
        // Windows
        if (function_exists('com_create_guid') === true) {
            if ($trim === true)
                return trim(com_create_guid(), '{}');
            else
                return com_create_guid();
        }
    
        // OSX/Linux
        if (function_exists('openssl_random_pseudo_bytes') === true) {
            $data = openssl_random_pseudo_bytes(16);
            $data[6] = chr(ord($data[6]) & 0x0f | 0x40);    // set version to 0100
            $data[8] = chr(ord($data[8]) & 0x3f | 0x80);    // set bits 6-7 to 10
            return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }
    
        // Fallback (PHP 4.2+)
        mt_srand((double)microtime() * 10000);
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45);                  // "-"
        $lbrace = $trim ? "" : chr(123);    // "{"
        $rbrace = $trim ? "" : chr(125);    // "}"
        $guidv4 = $lbrace.
                  substr($charid,  0,  8).$hyphen.
                  substr($charid,  8,  4).$hyphen.
                  substr($charid, 12,  4).$hyphen.
                  substr($charid, 16,  4).$hyphen.
                  substr($charid, 20, 12).
                  $rbrace;
        return $guidv4;
    }
}

?>