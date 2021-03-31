<?php 
//Classes
#region globalFunctions class (static functions)
class globalFunctions
{
	//Functions
	//static helper functions
	static function backspace($inString)
	{
		return substr($inString,0,-1);
	}
	
	static function parseNumber($inNumberString)
	{
		$inNumberString = str_replace(",",".",$inNumberString);			
		return floatval($inNumberString);
	}
	
	static function changeSign($inNumberString)
	{
		$nullStr = "0";
		if(strcmp($inNumberString, $nullStr) !== 0)
		{
			if($inNumberString[0] == "-")
			{
				$inNumberString = str_replace("-","",$inNumberString);	
			}
			else
			{
				$inNumberString = "-".$inNumberString;	
			}		
		}
		return $inNumberString;
	}

	
	static function addDigitToCurrentNumber($inCurrentStringNumb, $inInputedDigit, $isPi = false, $isE = false, $funct = null)
	{
		$nullStr = "0";
		if(strcmp($inCurrentStringNumb, $nullStr) !== 0 && !$isPi && !$isE)
		{
			return $inCurrentStringNumb.$inInputedDigit;
		}
		else if(($isPi || $isE) && $funct != null)
		{
			$num = $funct->parseNumber($inInputedDigit);
			$currentNum = $funct->parseNumber($inCurrentStringNumb);
			if($currentNum == 0)
			{
				$currentNum = 1;
			}
			return $num*$currentNum;			
		}
		
		return $inInputedDigit;
	}
	

	static function addCommaToCurrentNumber($inCurrentStringNumb)
	{
		$commaStr=".";
		if(strpos($inCurrentStringNumb, $commaStr) !== false)
		{
			return $inCurrentStringNumb;
		} 
		return $inCurrentStringNumb.$commaStr;
	}
	
	//calculating functions
	static function invokeEqual($inOperator, $inFirstNumb, $inSecondNumb, $funct = null)
	{
		switch($inOperator)
        {
			#region Basic operations
            case '+':
            return $inFirstNumb + $inSecondNumb;
            break;

            case '-':
            return $inFirstNumb - $inSecondNumb;
            break;

            case '*':
            return $inFirstNumb * $inSecondNumb;
            break;

            case '/':
			if($inSecondNumb == 0){ return "You can't divide with 0";}
            return $inFirstNumb / $inSecondNumb;
            break;
			
			case 'mod':
			if($inSecondNumb == 0){ return "You can't mod with 0";}
            return $inFirstNumb % $inSecondNumb;
            break;
			#endregion
			
			#region Other operations
			case '^':
            return pow ( $inFirstNumb , $inSecondNumb );
            break;
			
			case '%':
            return $inFirstNumb * (100/$inSecondNumb );
            break;
			
			case 'x^2':
            return pow ( $inFirstNumb , 2 );
            break;
			
			case '^':
            return pow ( $inFirstNumb , $inSecondNumb );
            break;
			
			case '10^x':
            return pow ( 10 , $inFirstNumb );
            break;
			
			case 'e^x':
            return pow ( 2.7182818284 , $inFirstNumb );
            break;
			
			case '1/x':
            return 1/$inFirstNumb;
            break;
			
			case 'abs':
            return abs($inFirstNumb);
            break;
			
			case 'sqrt':
            return sqrt($inFirstNumb);
            break;
			
			case 'fact':
			$factorial = 1;  
            for($i=1; $i<=$inFirstNumb; $i++)   
			{  
				$factorial = $factorial * $i;  
			}  
			return $factorial;
            break;
			
			case 'sin':
			return sin ($inFirstNumb);
            break;
			
			case 'cos':
			return cos ($inFirstNumb);
            break;
			
			case 'ln':
			return log(  $inFirstNumb );
            break;
		
			case 'log10':
			return log10 ($inFirstNumb);
            break;
			

			#endregion
			
            default:
            return "Sorry No command found";
        }  
	}
	
}//[class]
#endregion


//Properties
#region Properties
$currentNumberStr = $_POST['stored_currentNumberStr'];
if(empty($currentNumberStr))
{
	$currentNumberStr = "0";
}
$upperDisplay = $_POST['stored_upperDisplay'];
if(empty($upperDisplay))
{
	$upperDisplay = "0";
}
$operatorInvoked = boolval($_POST['stored_operatorInvoked']);
if(empty($operatorInvoked))
{
	$operatorInvoked = false;
}
$firstNumberEntered = boolval($_POST['stored_firstNumberEntered']);
if(empty($firstNumberEntered))
{
	$firstNumberEntered = false;
}
$secondNumberEntered = boolval($_POST['stored_secondNumberEntered']);
if(empty($secondNumberEntered))
{
	$secondNumberEntered = false;
}

$firstNumber = floatval($_POST['stored_firstNumber']);
if(empty($firstNumber))
{
	$firstNumber = 0.0;
}
$secondNumber = floatval($_POST['stored_secondNumber']);
if(empty($secondNumber))
{
	$secondNumber = 0.0;
}
$operator = $_POST['stored_operator'];
if(empty($operator))
{
	$operator = "none";
}
$operationFinished = boolval($_POST['stored_operationFinished']);
if(empty($operationFinished))
{
	$operationFinished = false;
}


//main display property
$mainDisplay = "0";
//objects
$functions = new globalFunctions();


//Debug visiblity
$typeInput = "";
$isDebugMode = false;
if($isDebugMode)
{
	$typeInput = "text";
}
else
{
	$typeInput = "hidden";
}
#endregion



//Inputs posts
#region Other inputs
if(isset($_POST['input_percent']))
{
	$operationFinished =false;
	$operator = "%";
	$operatorInvoked = true;
	$firstNumberEntered = true;
	$firstNumber = $functions->parseNumber($currentNumberStr);
	$upperDisplay = $currentNumberStr." ".$operator." ";
	$currentNumberStr = "0";
}
else if(isset($_POST['input_factoriel'])) //without second number funct
{
	$operationFinished =false;
	$tmp_operator = "fact";

	$tmp_number = $functions->parseNumber($currentNumberStr);
	if(!$firstNumberEntered)
	{
		$upperDisplay = "";
	}
	$upperDisplay = $upperDisplay."!".$currentNumberStr;
	$currentNumberStr = $functions-> invokeEqual($tmp_operator, $tmp_number, $secondNumber,$functions);
	if($firstNumberEntered && $operatorInvoked)
	{
		$secondNumber = $functions->parseNumber($currentNumberStr);
		$currentNumberStr = $functions-> invokeEqual($operator, $firstNumber, $secondNumber);
		$firstNumberEntered = false;
		$operatorInvoked = false;
		$operationFinished =true;
		$upperDisplay = $upperDisplay." =";
	}
	else
	{
		$firstNumber = $tmp_number;
		$firstNumberEntered = true;
	}
}
else if(isset($_POST['input_oneDivideNum'])) //without second number funct
{
	$operationFinished =false;
	$tmp_operator = "1/x";

	$tmp_number = $functions->parseNumber($currentNumberStr);
	if(!$firstNumberEntered)
	{
		$upperDisplay = "";
	}
	$upperDisplay = $upperDisplay."1/".$currentNumberStr;
	$currentNumberStr = $functions-> invokeEqual($tmp_operator, $tmp_number, $secondNumber);
	if($firstNumberEntered && $operatorInvoked)
	{
		$secondNumber = $functions->parseNumber($currentNumberStr);
		$currentNumberStr = $functions-> invokeEqual($operator, $firstNumber, $secondNumber);
		$firstNumberEntered = false;
		$operatorInvoked = false;
		$operationFinished =true;
		$upperDisplay = $upperDisplay." = ";
	}
	else
	{
		$firstNumber = $tmp_number;
		$firstNumberEntered = true;
	}
}
else if(isset($_POST['input_abs'])) //without second number funct (old way -> just here)
{
	$operationFinished =false;
	$operator = "abs";
	$operatorInvoked = true;
	$firstNumberEntered = true;
	$firstNumber = $functions->parseNumber($currentNumberStr);
	$upperDisplay = "|".$currentNumberStr."| =";

	$currentNumberStr = $functions-> invokeEqual($operator, $firstNumber, $secondNumber);	
	$firstNumberEntered = false;
	$operatorInvoked = false;
	$operationFinished =true;
}
#endregion

#region Power and Sqrt inputs
else if(isset($_POST['input_sqrt'])) //without second number funct
{
	$operationFinished =false;
	$tmp_operator = "sqrt";

	$tmp_number = $functions->parseNumber($currentNumberStr);
	if(!$firstNumberEntered)
	{
		$upperDisplay = "";
	}
	$upperDisplay = $upperDisplay."√".$currentNumberStr." ";
	$currentNumberStr = $functions-> invokeEqual($tmp_operator, $tmp_number, $secondNumber);
	if($firstNumberEntered && $operatorInvoked)
	{
		$secondNumber = $functions->parseNumber($currentNumberStr);
		$currentNumberStr = $functions-> invokeEqual($operator, $firstNumber, $secondNumber);
		$firstNumberEntered = false;
		$operatorInvoked = false;
		$operationFinished =true;
		$upperDisplay = $upperDisplay." =";
	}
	else
	{
		$firstNumber = $tmp_number;
		$firstNumberEntered = true;
	}
}
else if(isset($_POST['input_ePowerX'])) //without second number funct
{
	$operationFinished =false;
	$tmp_operator = "e^x";

	$tmp_number = $functions->parseNumber($currentNumberStr);
	if(!$firstNumberEntered)
	{
		$upperDisplay = "";
	}
	$upperDisplay = $upperDisplay."e^".$currentNumberStr;
	$currentNumberStr = $functions-> invokeEqual($tmp_operator, $tmp_number, $secondNumber);
	if($firstNumberEntered && $operatorInvoked)
	{
		$secondNumber = $functions->parseNumber($currentNumberStr);
		$currentNumberStr = $functions-> invokeEqual($operator, $firstNumber, $secondNumber);
		$firstNumberEntered = false;
		$operatorInvoked = false;
		$operationFinished =true;
		$upperDisplay = $upperDisplay." =";
	}
	else
	{
		$firstNumber = $tmp_number;
		$firstNumberEntered = true;
	}
}
else if(isset($_POST['input_xPowerY']))
{
	$operationFinished =false;
	$operator = "^";
	$operatorInvoked = true;
	$firstNumberEntered = true;
	$firstNumber = $functions->parseNumber($currentNumberStr);
	$upperDisplay = $currentNumberStr." ".$operator." ";
	$currentNumberStr = "0";
}
else if(isset($_POST['input_xPower2'])) //without second number funct
{
	$operationFinished =false;
	$tmp_operator = "x^2";

	$tmp_number = $functions->parseNumber($currentNumberStr);
	if(!$firstNumberEntered)
	{
		$upperDisplay = "";
	}
	$upperDisplay = $upperDisplay.$currentNumberStr."^2";
	$currentNumberStr = $functions-> invokeEqual($tmp_operator, $tmp_number, $secondNumber);
	if($firstNumberEntered && $operatorInvoked)
	{
		$secondNumber = $functions->parseNumber($currentNumberStr);
		$currentNumberStr = $functions-> invokeEqual($operator, $firstNumber, $secondNumber);
		$firstNumberEntered = false;
		$operatorInvoked = false;
		$operationFinished =true;
		$upperDisplay = $upperDisplay." = ";
	}
	else
	{
		$firstNumber = $tmp_number;
		$firstNumberEntered = true;
	}
}
else if(isset($_POST['input_10PowerX'])) //without second number funct
{
	$operationFinished =false;
	$tmp_operator = "10^x";

	$tmp_number = $functions->parseNumber($currentNumberStr);
	if(!$firstNumberEntered)
	{
		$upperDisplay = "";
	}
	$upperDisplay = $upperDisplay."10^".$currentNumberStr;
	$currentNumberStr = $functions-> invokeEqual($tmp_operator, $tmp_number, $secondNumber);
	if($firstNumberEntered && $operatorInvoked)
	{
		$secondNumber = $functions->parseNumber($currentNumberStr);
		$currentNumberStr = $functions-> invokeEqual($operator, $firstNumber, $secondNumber);
		$firstNumberEntered = false;
		$operatorInvoked = false;
		$operationFinished =true;
		$upperDisplay = $upperDisplay." =";
	}
	else
	{
		$firstNumber = $tmp_number;
		$firstNumberEntered = true;
	}
}

#endregion

#region Trigonometry inputs
else if(isset($_POST['input_sin'])) //without second number funct
{
	$operationFinished =false;
	$tmp_operator = "sin";

	$tmp_number = $functions->parseNumber($currentNumberStr);
	if(!$firstNumberEntered)
	{
		$upperDisplay = "";
	}
	$upperDisplay = $upperDisplay."sin(".$currentNumberStr.")";
	$currentNumberStr = $functions-> invokeEqual($tmp_operator, $tmp_number, $secondNumber,$functions);
	if($firstNumberEntered && $operatorInvoked)
	{
		$secondNumber = $functions->parseNumber($currentNumberStr);
		$currentNumberStr = $functions-> invokeEqual($operator, $firstNumber, $secondNumber);
		$firstNumberEntered = false;
		$operatorInvoked = false;
		$operationFinished =true;
		$upperDisplay = $upperDisplay." =";
	}
	else
	{
		$firstNumber = $tmp_number;
		$firstNumberEntered = true;
	}
}
else if(isset($_POST['input_cos'])) //without second number funct
{
	$operationFinished =false;
	$tmp_operator = "cos";

	$tmp_number = $functions->parseNumber($currentNumberStr);
	if(!$firstNumberEntered)
	{
		$upperDisplay = "";
	}
	$upperDisplay = $upperDisplay."cos(".$currentNumberStr.")";
	$currentNumberStr = $functions-> invokeEqual($tmp_operator, $tmp_number, $secondNumber,$functions);
	if($firstNumberEntered && $operatorInvoked)
	{
		$secondNumber = $functions->parseNumber($currentNumberStr);
		$currentNumberStr = $functions-> invokeEqual($operator, $firstNumber, $secondNumber);
		$firstNumberEntered = false;
		$operatorInvoked = false;
		$operationFinished =true;
		$upperDisplay = $upperDisplay." =";
	}
	else
	{
		$firstNumber = $tmp_number;
		$firstNumberEntered = true;
	}
}
#endregion

#region Logarithmic inputs
else if(isset($_POST['input_log'])) //without second number funct
{
	$operationFinished =false;
	$tmp_operator = "log10";

	$tmp_number = $functions->parseNumber($currentNumberStr);
	if(!$firstNumberEntered)
	{
		$upperDisplay = "";
	}
	$upperDisplay = $upperDisplay."log(base10)(".$currentNumberStr.")";
	$currentNumberStr = $functions-> invokeEqual($tmp_operator, $tmp_number, $secondNumber,$functions);
	if($firstNumberEntered && $operatorInvoked)
	{
		$secondNumber = $functions->parseNumber($currentNumberStr);
		$currentNumberStr = $functions-> invokeEqual($operator, $firstNumber, $secondNumber);
		$firstNumberEntered = false;
		$operatorInvoked = false;
		$operationFinished =true;
		$upperDisplay = $upperDisplay." =";
	}
	else
	{
		$firstNumber = $tmp_number;
		$firstNumberEntered = true;
	}
}
else if(isset($_POST['input_ln'])) //without second number funct
{
	$operationFinished =false;
	$tmp_operator = "ln";

	$tmp_number = $functions->parseNumber($currentNumberStr);
	if(!$firstNumberEntered)
	{
		$upperDisplay = "";
	}
	$upperDisplay = $upperDisplay."ln(".$currentNumberStr.")";
	$currentNumberStr = $functions-> invokeEqual($tmp_operator, $tmp_number, $secondNumber,$functions);
	if($firstNumberEntered && $operatorInvoked)
	{
		$secondNumber = $functions->parseNumber($currentNumberStr);
		$currentNumberStr = $functions-> invokeEqual($operator, $firstNumber, $secondNumber);
		$firstNumberEntered = false;
		$operatorInvoked = false;
		$operationFinished =true;
		$upperDisplay = $upperDisplay." =";
	}
	else
	{
		$firstNumber = $tmp_number;
		$firstNumberEntered = true;
	}
}

#endregion

#region Basic operations inputs (+,-,*,/)
else if(isset($_POST['input_times']))
{
	$operationFinished =false;
	 $operator = $_POST['input_times'];
	 $operatorInvoked = true;
	 $firstNumberEntered = true;
	 $firstNumber = $functions->parseNumber($currentNumberStr);
	 $upperDisplay = $currentNumberStr." ".$operator." ";
	 $currentNumberStr = "0";
}
else if(isset($_POST['input_divide']))
{
	$operationFinished =false;
	 $operator = $_POST['input_divide'];
	 $operatorInvoked = true;
	 $firstNumberEntered = true;
	 $firstNumber = $functions->parseNumber($currentNumberStr);
	 $upperDisplay = $currentNumberStr." ".$operator." ";
	 $currentNumberStr = "0";
}
else if(isset($_POST['input_minus']))
{
	$operationFinished =false;
	 $operator = $_POST['input_minus'];
	 $operatorInvoked = true;
	 $firstNumberEntered = true;
	 $firstNumber = $functions->parseNumber($currentNumberStr);
	 $upperDisplay = $currentNumberStr." ".$operator." ";
	 $currentNumberStr = "0";
}
else if(isset($_POST['input_plus']))
{
	$operationFinished =false;
	 $operator = $_POST['input_plus'];
	 $operatorInvoked = true;
	 $firstNumberEntered = true;
	 $firstNumber = $functions->parseNumber($currentNumberStr);
	 $upperDisplay = $currentNumberStr." ".$operator." ";
	 $currentNumberStr = "0";
}
else if(isset($_POST['input_mod']))
{
	$operationFinished =false;
	 $operator = $_POST['input_mod'];
	 $operatorInvoked = true;
	 $firstNumberEntered = true;
	 $firstNumber = $functions->parseNumber($currentNumberStr);
	 $upperDisplay = $currentNumberStr." ".$operator." ";
	 $currentNumberStr = "0";
}
#endregion

#region Comma & Sign change inputs
else if(isset($_POST['input_comma']))
{
	if($operationFinished)
	{
		$currentNumberStr = "0";
		$upperDisplay = "0";		
	}
	$operationFinished =false;
	$currentNumberStr = $functions->addCommaToCurrentNumber($currentNumberStr);
}
else if(isset($_POST['input_changeSign']))
{
	if($operationFinished)
	{
		$currentNumberStr = "0";
		$upperDisplay = "0";		
	}
	$operationFinished =false;
	$currentNumberStr = $functions->changeSign($currentNumberStr);
}
#endregion

#region Clear $ Backspace input
else if(isset($_POST['input_clear']))
{	
	$firstNumberEntered = false;
	$secondNumberEntered = false;
	$operatorInvoked = false;
	$operationFinished = false;
	$firstNumber = 0;
	$secondNumber = 0;
	$upperDisplay = "0";
	$currentNumberStr = "0"; 
}
else if(isset($_POST['input_bakcspace']))
{
	if($operationFinished)
	{
		$firstNumberEntered = false;
		$secondNumberEntered = false;
		$operatorInvoked = false;
		$operationFinished = false;
		$firstNumber = 0;
		$secondNumber = 0;
		$upperDisplay = "0";
		$currentNumberStr = "0"; 
	}
	$currentNumberStr = $functions->backspace($currentNumberStr);
}
#endregion

#region Numeric inputs
else if(isset($_POST['input_0']))
{
	if($operationFinished)
	{
		$currentNumberStr = "0";
		$upperDisplay = "0";	
	}
	$operationFinished =false;
	$inputedDigit = $_POST['input_0'];
	$currentNumberStr = $functions->addDigitToCurrentNumber($currentNumberStr,$inputedDigit);
}
else if(isset($_POST['input_1']))
{
	if($operationFinished)
	{
		$currentNumberStr = "0";
		$upperDisplay = "0";
	}
	$operationFinished =false;
	$inputedDigit = $_POST['input_1'];
	$currentNumberStr = $functions->addDigitToCurrentNumber($currentNumberStr,$inputedDigit);
}
else if(isset($_POST['input_2']))
{
	if($operationFinished)
	{
		$currentNumberStr = "0";
		$upperDisplay = "0";
	}
	$operationFinished =false;
	$inputedDigit = $_POST['input_2'];
	$currentNumberStr = $functions->addDigitToCurrentNumber($currentNumberStr,$inputedDigit);
}
else if(isset($_POST['input_3']))
{
	if($operationFinished)
	{
		$currentNumberStr = "0";
		$upperDisplay = "0";
	}
	$operationFinished =false;
	$inputedDigit = $_POST['input_3'];
	$currentNumberStr = $functions->addDigitToCurrentNumber($currentNumberStr,$inputedDigit);
}
else if(isset($_POST['input_4']))
{
	if($operationFinished)
	{
		$currentNumberStr = "0";
		$upperDisplay = "0";		
	}
	$operationFinished =false;
	$inputedDigit = $_POST['input_4'];
	$currentNumberStr = $functions->addDigitToCurrentNumber($currentNumberStr,$inputedDigit);
}
else if(isset($_POST['input_5']))
{
	if($operationFinished)
	{
		$currentNumberStr = "0";
		$upperDisplay = "0";
	}
	$operationFinished =false;
	$inputedDigit = $_POST['input_5'];
	$currentNumberStr = $functions->addDigitToCurrentNumber($currentNumberStr,$inputedDigit);
}
else if(isset($_POST['input_6']))
{
	if($operationFinished)
	{
		$currentNumberStr = "0";
		$upperDisplay = "0";
	}
	$operationFinished =false;
	$inputedDigit = $_POST['input_6'];
	$currentNumberStr = $functions->addDigitToCurrentNumber($currentNumberStr,$inputedDigit);
}
else if(isset($_POST['input_7']))
{
	if($operationFinished)
	{
		$currentNumberStr = "0";
		$upperDisplay = "0";
	}
	$operationFinished =false;
	$inputedDigit = $_POST['input_7'];
	$currentNumberStr = $functions->addDigitToCurrentNumber($currentNumberStr,$inputedDigit);
}
else if(isset($_POST['input_8']))
{
	if($operationFinished)
	{
		$currentNumberStr = "0";
		$upperDisplay = "0";
	}
	$operationFinished =false;
	$inputedDigit = $_POST['input_8'];
	$currentNumberStr = $functions->addDigitToCurrentNumber($currentNumberStr,$inputedDigit);
}
else if(isset($_POST['input_9']))
{
	if($operationFinished)
	{
		$currentNumberStr = "0";
		$upperDisplay = "0";
	}
	$operationFinished =false;
	$inputedDigit = $_POST['input_9'];
	$currentNumberStr = $functions->addDigitToCurrentNumber($currentNumberStr,$inputedDigit);
}
#endregion

#region Pi & E constants
else if(isset($_POST['input_piConst']))
{
	if($operationFinished)
	{
		$currentNumberStr = "0";
		$upperDisplay = "0";
	}
	$operationFinished =false;
	$inputedDigit = pi();
	$currentNumberStr = $functions->addDigitToCurrentNumber($currentNumberStr,$inputedDigit,true,false,$functions);
}
else if(isset($_POST['input_eConst']))
{
	if($operationFinished)
	{
		$currentNumberStr = "0";
		$upperDisplay = "0";
	}
	$operationFinished =false;
	$inputedDigit = 2.7182818284;
	$currentNumberStr = $functions->addDigitToCurrentNumber($currentNumberStr,$inputedDigit,false,true,$functions);
}
#endregion

#region Equal input
else if(isset($_POST['input_equal']))
{
	if($firstNumberEntered && $operatorInvoked)
	{
		$secondNumber = $functions->parseNumber($currentNumberStr);
		$upperDisplay = $upperDisplay.$currentNumberStr." = ";
		$currentNumberStr = $functions-> invokeEqual($operator, $firstNumber, $secondNumber);
		
		$firstNumberEntered = false;
		$operatorInvoked = false;
		$operationFinished =true;
	} 
}
#endregion



//Set main display
if(empty($currentNumberStr) == false)
{
	$mainDisplay = $currentNumberStr;
}

?>

<html>
<head>
	<link rel="stylesheet" type="text/css" href="style.css">
	<title>Scientific calculator PHP</title>
</head>

<body>
	<h1 align="center">Calculator</h1>
	<hr></hr>

	<form action="" method="post">
	
		<table name="table_calculator" align="center" class="wrapper">			
			<tr >
				<td colspan="5">
					<h4  align="right">
					<?php
					// if(strlen($upperDisplay)>1 && $upperDisplay[0] == "0" && $upperDisplay[1] != "." && !$firstNumberEntered)
					// {
						// echo substr($upperDisplay,1);
					// }
					// else
					// {
						echo $upperDisplay;
					//}				
					?>
					</h4>
				</td>
			</tr>
				<tr>
				<td colspan="5">
					<h2 align="right"><?php echo $mainDisplay; ?></h2>
				</td>
			</tr>
		
			<tr>        
				<td><input class="symbol" type="submit" name="input_percent" value="%"></td>
				<td><input class="symbol" type="submit" name="input_piConst" value="π"></td>
				<td><input class="symbol" type="submit" name="input_eConst" value="e"></td>
				<td><input class="symbol" type="submit" name="input_clear" value="C"></td>
				<td><input class="symbol" type="submit" name="input_bakcspace" value="⌫"></td>
			</tr>
			
			<tr>
				<td><input type="submit" name="input_xPower2" value="x^2"></td>
				<td><input type="submit" name="input_oneDivideNum" value="1/x"></td>
				<td><input type="submit" name="input_abs" value="|x|"></td>
				<td><input type="submit" name="input_sqrt" value="√x"></td>
				<td><input type="submit" name="input_mod" value="mod"></td>
			</tr>

			<tr>
				<td><input type="submit" name="input_xPowerY" value="^"></td>
				<td><input type="submit" name="input_ePowerX" value="e^x"></td>
				<td><input type="submit" name="input_10PowerX" value="10^x"></td>
				<td><input type="submit" name="input_factoriel" value="n!"></td>
				<td><input type="submit" name="input_divide" value="/"></td>
			</tr>
				
			<tr>
				<td><input type="submit" name="input_cos" value="cos"></td>
				<td><input class ="number" type="submit" name="input_7" value="7"></td>
				<td><input class ="number" type="submit" name="input_8" value="8"></td>
				<td><input class ="number" type="submit" name="input_9" value="9"></td>
				<td><input type="submit" name="input_times" value="*"></td>
			</tr>

			<tr>
				<td><input type="submit" name="input_sin" value="sin"></td>
				<td><input class ="number" type="submit" name="input_4" value="4"></td>
				<td><input class ="number" type="submit" name="input_5" value="5"></td>
				<td><input class ="number" type="submit" name="input_6" value="6"></td>
				<td><input type="submit" name="input_minus" value="-"></td>
			</tr>
			
			<tr>
				<td><input type="submit" name="input_log" value="log10"></td>
				<td><input class ="number"  type="submit" name="input_1" value="1"></td>
				<td><input class ="number" type="submit" name="input_2" value="2"></td>
				<td><input class ="number" type="submit" name="input_3" value="3"></td>
				<td><input type="submit" name="input_plus" value="+"></td>
			</tr>
	
			<tr>
				<td><input type="submit" name="input_ln" value="ln"></td>
				<td><input type="submit" name="input_changeSign" value="±"></td>
				<td><input class ="number" type="submit" name="input_0" value="0"></td>
				<td><input type="submit" name="input_comma" value=","></td>
				<td><input class="equal" type="submit" name="input_equal" value="="></td>
			</tr>

		</table>
		
		<?php
		#region Debug mode info:
			echo "<input type=\"$typeInput\" name=\"stored_upperDisplay\" value=\"$upperDisplay\"><br>";
			if($isDebugMode)
			{
				echo "<p>upperDisplay: </p><br>";
			}
			echo "<input type=\"$typeInput\" name=\"stored_currentNumberStr\" value=\"$currentNumberStr\"><br>";
			if($isDebugMode)
			{
				echo "<p>currentNumberStr: </p><br>";
			}
			echo "<input type=\"$typeInput\" name=\"stored_operatorInvoked\" value=\"$operatorInvoked\"><br>";
			if($isDebugMode)
			{
				echo "<p>operatorInvoked: </p><br>";
			}
			echo "<input type=\"$typeInput\" name=\"stored_firstNumberEntered\" value=\"$firstNumberEntered\"><br>";
			if($isDebugMode)
			{
				echo "<p>firstNumberEntered: </p><br>";
			}
			echo "<input type=\"$typeInput\" name=\"stored_secondNumberEntered\" value=\"$secondNumberEntered\"><br>";
			if($isDebugMode)
			{
				echo "<p>secondNumberEntered: </p><br>";
			}
			echo "<input type=\"$typeInput\" name=\"stored_firstNumber\" value=\"$firstNumber\"><br>";
			if($isDebugMode)
			{
				echo "<p>firstNumber: </p><br>";
			}
			echo "<input type=\"$typeInput\" name=\"stored_secondNumber\" value=\"$secondNumber\"><br>";
			if($isDebugMode)
			{
				echo "<p>secondNumber: </p><br>";
			}
			echo "<input type=\"$typeInput\" name=\"stored_operator\" value=\"$operator\"><br>";
			if($isDebugMode)
			{
				echo "<p>operator: </p><br>";
			}
			echo "<input type=\"$typeInput\" name=\"stored_operationFinished\" value=\"$operationFinished\"><br>";
			if($isDebugMode)
			{
				echo "<p>operation finished: </p><br>";
			}
			#endregion
		?>
		
	</form>
</body>
</html>