﻿<?php
include_once 'templates/style.css';

if(!@$_POST['database'])
{
	die($lang['dieDatabaseChoose']);
}
else
{
	include_once 'config.inc.php';

	$transport->open();

	$client->execute('add jar '.$env['hive_jar']);
	$client->execute('use '.$_POST['database']);
	
	 if("" == $_POST['newtablename'] || "" == $_POST['fieldnums'])
	 {
		echo "<script>alert('".$lang['addTableAlert']."'); history.back()</script>";
	 }
	 else
	 {
		if(!@$_POST['field_name'] || !@$_POST['field_type'])
		{
			echo "<form name=newTable method=post>";
			echo "<table border=1 cellspacing=1 cellpadding=3>";
			echo "<tr bgcolor=\"#FFFF99\">
					  <td>".$lang['fieldName']."</td>
					  <td>".$lang['fieldType']."</td>
					  <td>".$lang['comment']."</td>
				  </tr>";
			$type = array('string'=>'String','tinyint'=>'Tiny int(3)','smallint'=>'Small int(5)','int'=>'Int(10)','bigint'=>'Big int(19)','double'=>'Double',
						//'map'=>'Map','structs'=>'Structs','arrays'=>'Arrays',
						'float'=>'Float','boolean'=>'Boolean');
			for ($i = 0; $i < $_POST['fieldnums']; $i++)
			{
				if(($i % 2) == 0)
				{
					$color = $env['trColor1'];
				}
				else
				{
					$color = $env['trColor2'];
				}
				echo "<tr bgcolor=".$color.">\n";
				//-------------
				echo "<td>\n";
				echo "<input type=text name=field_name[]>\n";
				echo "</td>\n";
				//-------------
				echo "<td>\n";
				echo "<select name=field_type[]>";
				foreach($type as $kk => $vv)
				{
					echo "<option value=".$kk.">".$vv."</option>";
				}
				echo "</select>";
				echo "</td>\n";
				//-------------
				echo "<td>\n";
				echo "<input type=text name=comment[]>\n";
				echo "</td>\n";
				//-------------
				echo "</tr>\n";
			}
			echo "<input type=hidden name=database value=".$_POST['database'].">";
			echo "<input type=hidden name=newtablename value=".$_POST['newtablename'].">";
			echo "<input type=hidden name=fieldnums value=".$_POST['fieldnums'].">";
			//echo "<input type=hidden name=extenal value=".$_POST['extenal'].">";
			echo "</table><br>";
			if(@$_POST['extenal'] == 1)
			{
				echo $lang['extenalPath']."<input type=text name=extenal><br>".$lang['delimiter']."<input type=text name=delimiter>";
			}
			echo "<input type=submit name=submit value=".$lang['submit'].">";
			echo "<input type=button name=cancel value=".$lang['cancel']." onclick=\"javascript:window.location='dbStructure.php?database=".$_POST['database']."'\">";
			echo "</form>";
		}
		else
		{
			$sql = "CREATE TABLE IF NOT EXISTS ".$_POST['newtablename']." (";
			$i = 0;
			$str = "";
			while ("" != @$_POST['field_name'][$i])
			{
				$str .= $_POST['field_name'][$i]." ".$_POST['field_type'][$i]." COMMENT '".$_POST['comment'][$i]."',";
				$i++;
			}
			$str = substr($str,0,-1);
			$sql = $sql.$str.")";
			echo $sql."<br>";
			$client->execute($sql);
			echo "<script>alert('".$lang['createTableSuccess']."');window.location='dbStructure.php?database=".$_POST['database']."';</script>";
		}
	}
	$transport->close();
}