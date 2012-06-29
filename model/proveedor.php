<?php
/*
 * This file is part of FacturaSctipts
 * Copyright (C) 2012  Carlos Garcia Gomez  neorazorx@gmail.com
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 * 
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

require_once 'base/fs_model.php';
require_once 'model/albaran_proveedor.php';
require_once 'model/subcuenta.php';

class subcuenta_proveedor extends fs_model
{
   public $codproveedor;
   public $codsubcuenta;
   public $codejercicio;
   public $idsubcuenta;
   public $id; /// pkey
   
   public function __construct($s=FALSE)
   {
      parent::__construct('co_subcuentasprov');
      if($s)
      {
         $this->codproveedor = $s['codproveedor'];
         $this->codsubcuenta = $s['codsubcuenta'];
         $this->codejercicio = $s['codejercicio'];
         $this->idsubcuenta = $this->intval($s['idsubcuenta']);
         $this->id = $this->intval($s['id']);
      }
      else
      {
         $this->codproveedor = NULL;
         $this->codsubcuenta = NULL;
         $this->codejercicio = NULL;
         $this->idsubcuenta = NULL;
         $this->id = NULL;
      }
   }
   
   protected function install()
   {
      return "";
   }
   
   public function get_subcuenta()
   {
      $subc = new subcuenta();
      return $subc->get($this->idsubcuenta);
   }

   public function exists()
   {
      if( is_null($this->id) )
         return FALSE;
      else
         return $this->db->select("SELECT * FROM ".$this->table_name." WHERE id = '".$this->id."';");
   }
   
   public function save()
   {
      ;
   }
   
   public function delete()
   {
      return $this->db->exec("DELETE FROM ".$this->table_name." WHERE id = '".$this->id."';");
   }
   
   public function all_from_proveedor($codprov)
   {
      $sclist = array();
      $subcs = $this->db->select("SELECT * FROM ".$this->table_name." WHERE codproveedor = '".$codprov."'
         ORDER BY codejercicio DESC;");
      if($subcs)
      {
         foreach($subcs as $s)
            $sclist[] = new subcuenta_proveedor($s);
      }
      return $sclist;
   }
}

class direccion_proveedor extends fs_model
{
   public $codproveedor;
   public $codpais;
   public $apartado;
   public $provincia;
   public $ciudad;
   public $codpostal;
   public $direccion;
   public $direccionppal;
   public $descripcion;
   public $id; /// pkey
   
   public function __construct($d=FALSE)
   {
      parent::__construct('dirproveedores');
      if($d)
      {
         $this->codproveedor = $d['codproveedor'];
         $this->codpais = $d['codpais'];
         $this->apartado = $d['apartado'];
         $this->provincia = $d['provincia'];
         $this->ciudad = $d['ciudad'];
         $this->codpostal = $d['codpostal'];
         $this->direccion = $d['direccion'];
         $this->direccionppal = ($d['direccionppal'] == 't');
         $this->descripcion = $d['descripcion'];
         $this->id = $this->intval($d['id']);
      }
      else
      {
         $this->codproveedor = NULL;
         $this->codpais = NULL;
         $this->apartado = NULL;
         $this->provincia = NULL;
         $this->ciudad = NULL;
         $this->codpostal = NULL;
         $this->direccion = NULL;
         $this->direccionppal = TRUE;
         $this->descripcion = NULL;
         $this->id = NULL;
      }
   }
   
   protected function install()
   {
      return "";
   }
   
   public function get($id)
   {
      $dir = $this->db->select("SELECT * FROM ".$this->table_name." WHERE id = '".$id."';");
      if($dir)
         return new direccion_proveedor($dir[0]);
      else
         return FALSE;
   }

   public function exists()
   {
      if( is_null($this->id) )
         return FALSE;
      else
         return $this->db->select("SELECT * FROM ".$this->table_name." WHERE id = '".$this->id."';");
   }
   
   public function save()
   {
      if( $this->exists() )
      {
         $sql = "UPDATE ".$this->table_name." SET codproveedor = ".$this->var2str($this->codproveedor).",
            codpais = ".$this->var2str($this->codpais).", apartado = ".$this->var2str($this->apartado).",
            provincia = ".$this->var2str($this->provincia).", ciudad = ".$this->var2str($this->ciudad).",
            codpostal = ".$this->var2str($this->codpostal).", direccion = ".$this->var2str($this->direccion).",
            direccionppal = ".$this->var2str($this->direccionppal).", descripcion = ".$this->var2str($this->descripcion)."
            WHERE id = '".$this->id."';";
      }
      else
      {
         $sql = "INSERT INTO ".$this->table_name." (codproveedor,codpais,apartado,provincia,ciudad,codpostal,direccion,
            direccionppal,descripcion) VALUES (".$this->var2str($this->codproveedor).",".$this->var2str($this->codpais).",
            ".$this->var2str($this->apartado).",".$this->var2str($this->provincia).",".$this->var2str($this->ciudad).",
            ".$this->var2str($this->codpostal).",".$this->var2str($this->direccion).",".$this->var2str($this->direccionppal).",
            ".$this->var2str($this->descripcion).");";
      }
      return $this->db->exec($sql);
   }
   
   public function delete()
   {
      return $this->db->exec("DELETE FROM ".$this->table_name." WHERE id = '".$this->id."';");
   }
   
   public function all_from_proveedor($codprov)
   {
      $dirlist = array();
      $dirs = $this->db->select("SELECT * FROM ".$this->table_name." WHERE codproveedor = '".$codprov."';");
      if($dirs)
      {
         foreach($dirs as $d)
            $dirlist[] = new direccion_proveedor($d);
      }
      return $dirlist;
   }
}

class proveedor extends fs_model
{
   public $codproveedor;
   public $nombre;
   public $nombrecomercial;
   public $cifnif;
   public $telefono1;
   public $telefono2;
   public $fax;
   public $email;
   public $web;
   public $codserie;
   public $coddivisa;
   public $codpago;
   public $observaciones;
   
   private static $default_proveedor;

   public function __construct($p=FALSE)
   {
      parent::__construct('proveedores');
      if($p)
      {
         $this->codproveedor = $p['codproveedor'];
         $this->nombre = $p['nombre'];
         $this->nombrecomercial = $p['nombrecomercial'];
         $this->cifnif = $p['cifnif'];
         $this->telefono1 = $p['telefono1'];
         $this->telefono2 = $p['telefono2'];
         $this->fax = $p['fax'];
         $this->email = $p['email'];
         $this->web = $p['web'];
         $this->codserie = $p['codserie'];
         $this->coddivisa = $p['coddivisa'];
         $this->codpago = $p['codpago'];
         $this->observaciones = $p['observaciones'];
      }
      else
      {
         $this->codproveedor = NULL;
         $this->nombre = '';
         $this->nombrecomercial = '';
         $this->cifnif = '';
         $this->telefono1 = '';
         $this->telefono2 = '';
         $this->fax = '';
         $this->email = '';
         $this->web = '';
         $this->codserie = NULL;
         $this->coddivisa = NULL;
         $this->codpago = NULL;
         $this->observaciones = '';
      }
   }
   
   public function observaciones_resume()
   {
      if($this->observaciones == '')
         return '-';
      else if( strlen($this->observaciones) < 60 )
         return $this->observaciones;
      else
         return substr($this->observaciones, 0, 50).'...';
   }
   
   public function url()
   {
      if( is_null($this->codproveedor) )
         return "index.php?page=general_proveedores";
      else
         return "index.php?page=general_proveedor&cod=".$this->codproveedor;
   }
   
   public function is_default()
   {
      if( isset(self::$default_proveedor) )
         return (self::$default_proveedor == $this->codproveedor);
      else if( !isset($_COOKIE['default_proveedor']) )
         return FALSE;
      else if($_COOKIE['default_proveedor'] == $this->codproveedor)
         return TRUE;
      else
         return FALSE;
   }
   
   public function set_default()
   {
      setcookie('default_proveedor', $this->codproveedor, time()+FS_COOKIES_EXPIRE);
      self::$default_proveedor = $this->codproveedor;
   }
   
   public function get_new_codigo()
   {
      $cod = $this->db->select("SELECT MAX(codproveedor::integer) as cod FROM ".$this->table_name.";");
      if($cod)
         return sprintf('%06s', (1 + intval($cod[0]['cod'])));
      else
         return '000001';
   }
   
   public function get_albaranes($offset=0)
   {
      $alb = new albaran_proveedor();
      return $alb->all_from_proveedor($this->codproveedor, $offset);
   }
   
   public function get_subcuentas()
   {
      $sublist = array();
      $subcp = new subcuenta_proveedor();
      foreach($subcp->all_from_proveedor($this->codproveedor) as $s)
         $sublist[] = $s->get_subcuenta();
      return $sublist;
   }
   
   public function get_subcuenta($eje)
   {
      $retorno = FALSE;
      $subcs = $this->get_subcuentas();
      foreach($subcs as $s)
      {
         if($s->codejercicio == $eje)
            $retorno = $s;
      }
      return $retorno;
   }
   
   public function get_direcciones()
   {
      $dir = new direccion_proveedor();
      return $dir->all_from_proveedor($this->codproveedor);
   }

   protected function install()
   {
      return '';
   }
   
   public function exists()
   {
      if( is_null($this->codproveedor) )
         return FALSE;
      else
         return $this->db->select("SELECT * FROM ".$this->table_name." WHERE codproveedor = '".$this->codproveedor."';");
   }
   
   public function save()
   {
      if( $this->exists() )
      {
         $sql = "UPDATE ".$this->table_name." SET nombre = ".$this->var2str($this->nombre).",
            nombrecomercial = ".$this->var2str($this->nombrecomercial).", cifnif = ".$this->var2str($this->cifnif).",
            telefono1 = ".$this->var2str($this->telefono1).", telefono2 = ".$this->var2str($this->telefono2).",
            fax = ".$this->var2str($this->fax).", email = ".$this->var2str($this->email).",
            web = ".$this->var2str($this->web).", codserie = ".$this->var2str($this->codserie).",
            coddivisa = ".$this->var2str($this->coddivisa).", codpago = ".$this->var2str($this->codpago).",
            observaciones = ".$this->var2str($this->observaciones)." WHERE codproveedor = '".$this->codproveedor."';";
      }
      else
      {
         $sql = "INSERT INTO ".$this->table_name." (codproveedor,nombre,nombrecomercial,cifnif,telefono1,telefono2,
            fax,email,web,codserie,coddivisa,codpago,observaciones) VALUES ('".$this->codproveedor."',
            ".$this->var2str($this->nombre).",".$this->var2str($this->nombrecomercial).",".$this->var2str($this->cifnif).",
            ".$this->var2str($this->telefono1).",".$this->var2str($this->telefono2).",".$this->var2str($this->fax).",
            ".$this->var2str($this->email).",".$this->var2str($this->web).",".$this->var2str($this->codserie).",
            ".$this->var2str($this->coddivisa).",".$this->var2str($this->codpago).",".$this->var2str($this->observaciones).");";
      }
      return $this->db->exec($sql);
   }
   
   public function delete()
   {
      return $this->db->exec("DELETE FROM ".$this->table_name." WHERE codproveedor = '".$this->codproveedor."';");
   }
   
   public function get($cod)
   {
      $prov = $this->db->select("SELECT * FROM ".$this->table_name." WHERE codproveedor = '".$cod."';");
      if($prov)
         return new proveedor($prov[0]);
      else
         return FALSE;
   }
   
   public function all($offset=0)
   {
      $provelist = array();
      $proveedores = $this->db->select_limit("SELECT * FROM ".$this->table_name." ORDER BY nombre ASC",
                                             FS_ITEM_LIMIT, $offset);
      if($proveedores)
      {
         foreach($proveedores as $p)
            $provelist[] = new proveedor($p);
      }
      return $provelist;
   }
   
   public function all_full()
   {
      $provelist = array();
      $proveedores = $this->db->select("SELECT * FROM ".$this->table_name." ORDER BY nombre ASC;");
      if($proveedores)
      {
         foreach($proveedores as $p)
            $provelist[] = new proveedor($p);
      }
      return $provelist;
   }
   
   public function search($query, $offset=0)
   {
      $prolist = array();
      $query = strtolower($query);
      $proveedores = $this->db->select_limit("SELECT * FROM ".$this->table_name." WHERE codproveedor ~~ '%".$query."%'
         OR lower(nombre) ~~ '%".$query."%' OR lower(nombrecomercial) ~~ '%".$query."%'
         OR lower(observaciones) ~~ '%".$query."%' ORDER BY nombre ASC", FS_ITEM_LIMIT, $offset);
      if($proveedores)
      {
         foreach($proveedores as $p)
            $prolist[] = new proveedor($p);
      }
      return $prolist;
   }
}

?>
