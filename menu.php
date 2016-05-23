<select name="menu">
	  <option selected>Selecciona una opción</option>	
	  <? while($reg=mysql_fetch_row($opciones)){?>
	   <option value="<?=$reg[0]?>"><?=$reg[0]?></option>
	  <? }?>      
      </select>
