<?php 
	function ASIGNAR_CADETE(){
		$consulta = new consulta(); // definimos un objeto consulta
		$filas = $consulta->asignar_cadete(); //accedemo al metodo asignar cadete(consulta) y almacenamos en la variable filas

		echo '<select name="RELA_EMPLEADO" class="form-control input-lg">';

			foreach ($filas as $fila){

				echo '<option value="'.$fila['id_empleados'].'">'.$fila['nombre_pers'].'</option>';

			}

		echo '</select>';

	}	
 ?>