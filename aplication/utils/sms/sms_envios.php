<?php
namespace MiProyecto{
	/**
	 * Description of sms_envios
	 *
	 * @author user
	 */

	class sms_envios {
		// Función para crear evento SMS en Google Calendar
		//Título indica el título del SMS; Texto indica parte del cuerpo del SMS;
		//Minutos indica la demora del aviso; Email indica el login de la cuenta
		//Password indica la contraseña de la cuenta
		public function send_sms($titulo, $texto='', $minutos=5, $email='johnjairo1984@gmail.com', $password='vane1307'){
				// load librerias //
			$this->load_zend_gdata();
			   // Nombre del servicio de Google Calendar
			$service = Zend_Gdata_Calendar::AUTH_SERVICE_NAME;
			$client = Zend_Gdata_ClientLogin::getHttpClient($email,$password,$service);
			$gdataCal = new Zend_Gdata_Calendar($client);
			$event = $gdataCal->newEventEntry();
			$event->title = $gdataCal->newTitle($titulo);
				// Añadimos texto
			if($texto!=''){
				$event->where = array($gdataCal->newWhere("--->>> ".$texto." <<<---"));
				$event->content = $gdataCal->newContent("--->>> $texto <<<---");
			}
				// Calculamos la hora de creación del evento con la demora incluida para que nos avise
			$time=time()+$minutos*60;
				// Hora en formato RFC 3339
			$endDate = $startDate = date("Y-m-d", $time);
			$endTime = $startTime = date("H:i", $time);
			$tzOffset = "+01";
			$when = $gdataCal->newWhen();
			$when->startTime = "{$startDate}T{$startTime}:00.000{$tzOffset}:00";
			$when->endTime = "{$endDate}T{$endTime}:00.000{$tzOffset}:00";
				// Añadimos el recordatorio SMS
			$reminder = $gdataCal->newReminder();
			$reminder->method = "sms";
				// Tiempo de adelanto (no tiene sentido en el ejemplo actual)
			$reminder->minutes = 0;
				// Aplicamos
			$when->reminders = array($reminder);
			$event->when = array($when);
				// Añadimos el evento a google calendar
			$newEvent = $gdataCal->insertEvent($event);
		}
		/***/
		private function load_zend_gdata(){
			// Carga manual de librerías Zend_Gdata
			ini_set('include_path',dirname(__FILE__).'/ZendGdata-1.12.3/library');
			require_once (dirname(__FILE__).'/ZendGdata-1.12.3/library/Zend/Loader.php');
			// Declaramos las clases
			Zend_Loader::loadClass('Zend_Gdata');
			Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
			Zend_Loader::loadClass('Zend_Gdata_Calendar');
			Zend_Loader::loadClass('Zend_Http_Client');
			Zend_Loader::loadClass('Zend_Gdata_Extension_When');
		}
	}
}
