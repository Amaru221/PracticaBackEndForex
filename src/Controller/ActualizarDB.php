<?php
// src/Controller/ActualizarDB.php
namespace App\Controller;
use \DOMDocument;
use \PDO;
use \SplFileInfo;
use \dateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Vela;
use App\Entity\Operacion;



class ActualizarDB extends AbstractController{
	

	public function leer_config($nombre, $esquema){
		$config = new DOMDocument();
		$config->load($nombre);
		$res = $config->schemaValidate($esquema);
		if ($res===FALSE){ 
		   throw new InvalidArgumentException("Revise fichero de configuración");
		} 		
		$datos = simplexml_load_file($nombre);	
		$ip = $datos->xpath("//ip");
		$nombre = $datos->xpath("//nombre");
		$usu = $datos->xpath("//usuario");
		$clave = $datos->xpath("//clave");	
		$cad = sprintf("mysql:dbname=%s;host=%s", $nombre[0], $ip[0]);
		$resul = [];
		$resul[] = $cad;
		$resul[] = $usu[0];
		$resul[] = $clave[0];
		return $resul;
	}


	/**
	* @Route("/actualizar", name="actualizar")
	*/
	public function funcionActualizar(){

		// conexión
		//$res = ActualizarDB::leer_config(dirname(__FILE__)."./configuracion.xml", dirname(__FILE__)."./configuracion.xsd");
		//$bd = new PDO($res[0], $res[1], $res[2]);

		if (isset($_POST['actualizar']))
		{

			$filename=$_FILES["filename"]["name"];
	  		$info = new SplFileInfo($filename);
	  		$extension = pathinfo($info->getFilename(), PATHINFO_EXTENSION);

	  		if($extension == 'csv')
	   		{
	   			$filename = $_FILES['filename']['tmp_name'];
				$handle = fopen($filename, "r");

				while( ($data = fgetcsv($handle, 1000, ";") ) !== FALSE )
				{
					$fecha = $data[0];
					//echo $fecha;
					$fecha = strtr($fecha, ".", "/");
					//echo $fecha;
					$fecha = new dateTime($fecha);
					$fecha = $fecha->getTimestamp();
					//echo $fecha."<br>";

					$apertura = $data[1]*100000;
					$alto = $data[2]*100000;
					$bajo = $data[3]*100000;
					$cierre = $data[4]*100000;
					$volumen = $data[5];

					$vela = new Vela();
					$vela->setFecha($fecha);
					$vela->setApertura($apertura);
					$vela->setAlto($alto);
					$vela->setBajo($bajo);
					$vela->setCierre($cierre);
					$vela->setVolumen($volumen);
					$entityManager = $this->getDoctrine()->getManager();
					$velaComprobar = $this->getDoctrine()->getRepository(Vela::class)->findBy(['fecha' => $fecha]);

					if(count($velaComprobar) <=0){
						$entityManager->persist($vela);
	    				$entityManager->flush();
					}else{
						echo "existe coincidencia<br>";
					}
					
	   			}

			}

		}


		return $this->render('actualizar.html.twig');
	}

	/**
	* @Route("/getPrecio", name="getPrecio")
	*/
	public function getPrecio(){
		$date = getdate();
		$segundos = $date['seconds'];
		$fecha = $date[0]-$segundos;
		$vela = $this->getDoctrine()->getRepository(Vela::class)->findBy(['fecha' => $fecha]);

		$array;
		foreach ($vela as $iteracion) {
			$array['id'] = $iteracion->getId();
			$array['fecha'] = $iteracion->getFecha();
			$array['apertura'] = $iteracion->getApertura();
			$array['alto'] = $iteracion->getAlto();
			$array['bajo'] = $iteracion->getBajo();
			$array['cierre'] = $iteracion->getCierre();
		}
		return new JsonResponse($array);
	}


	/**
	* @Route("/getOrdenes", name="getOrdenes")
	*/
	public function getOrdenes(){
		//obtendriamos el usuario mediante sesion

		$operacion = $this->getDoctrine()->getRepository(Operacion::class)->findBy(['usuario' => 1]);

		$array;
		foreach ($operacion as $iteracion) {
			$array['id'] = $iteracion->getId();
			$array['usuario'] = $iteracion->getUsuario();
			$array['fecha_op'] = $iteracion->getFecha_op();
			$array['tipo_op'] = $iteracion->getTipo_op();
			$array['entrada'] = $iteracion->getEntrada();
			$array['salida'] = $iteracion->getSalida();
			$array['estado'] = $iteracion->getEstado();
			$array['fecha_cierre'] = $iteracion->getFecha_Cierre();

		}
		return new JsonResponse($array);


	}


	/**
	* @Route("/operacion/compra", name="compra")
	*/
	public function compra(){
		$ultimaVela = new Vela();
		$ultimaVela = $this->getDoctrine()->getRepository(Vela::class)->findBy(array(),array('fecha'=>'DESC'),1,0);
		$ultimaVela = $ultimaVela[0];
		print_r($ultimaVela);

		//var_dump($results);
		$operacion = new Operacion();
		$operacion->setUsuario(1);
		$operacion->setFecha_op($ultimaVela->getFecha());
		$operacion->setFecha_Cierre(0);
		$operacion->setTipo_op(1);
		$operacion->setEntrada($ultimaVela->getApertura());
		$operacion->setSalida($ultimaVela->getCierre());
		$operacion->setEstado(1);

		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->persist($operacion);
	    $entityManager->flush();

		return new JsonResponse();
	}

	/**
	* @Route("/operacion/venta", name="venta")
	*/
	public function venta(){
		$ultimaVela = new Vela();
		$ultimaVela = $this->getDoctrine()->getRepository(Vela::class)->findBy(array(),array('fecha'=>'DESC'),1,0);
		$ultimaVela = $ultimaVela[0];
		print_r($ultimaVela);

		$operacion = new Operacion();
		$operacion->setUsuario(1);
		$operacion->setFecha_op($ultimaVela->getFecha());
		$operacion->setFecha_Cierre(0);
		$operacion->setTipo_op(0);
		$operacion->setEntrada($ultimaVela->getApertura());
		$operacion->setSalida($ultimaVela->getCierre());
		$operacion->setEstado(1);

		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->persist($operacion);
	    $entityManager->flush();


		return new JsonResponse();
	}


	/**
	* @Route("/operacion/cerrar/{id}", name="cerrar")
	*/
	public function cerrar($id){

		$operacion = $this->getDoctrine()->getRepository(Operacion::class)->findBy(['id'=>$id]);
		
		$ultimaVela = $this->getDoctrine()->getRepository(Vela::class)->findBy(array(),array('fecha'=>'DESC'),1,0);
		$ultimaVela = $ultimaVela[0];
		$fechaUltimaVela = $ultimaVela->getFecha();

		$operacionModificada = $operacion[0];
		$operacionModificada->setEstado(0);
		$operacionModificada->setFecha_Cierre($fechaUltimaVela);

		$entityManager = $this->getDoctrine()->getManager();
		$entityManager->persist($operacionModificada);
	    $entityManager->flush();


		return new JsonResponse();
	}




}


?>