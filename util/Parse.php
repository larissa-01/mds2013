<?php
require_once 'libs/excel_reader2.php';
class Parse{
	private $natureza;
	private $tempo;
	private $crime;
	private $categoria;
	private $dados;
	
	public function __construct($planilha){
		$this->dados = new Spreadsheet_Excel_Reader("files/".$planilha,"UTF-8");
		if($planilha = "s�rie hist�rica - 2001 - 2012 2.xls"){
			$this->parseDeSerieHistorica();
		}
		else if($planilha = "JAN_SET_2011_12  POR REGIAO ADM_2.xls"){
			$this->parsePorRegiao();
		}
		else{
			$this->parseDeQuadrimestre();
		}	
	}
	//ParsePorSerieHistorica 
	public function parseDeSerieHistorica(){
		$numeroLinhas = 40;
		$numeroColunas = 15;
		//loop que pega a natureza
		for($i=0,$auxCategoria=0;$i<$numeroLinhas;$i++){
			if($i == 2){
				$this->categoria[$auxCategoria] = $this->dados->val($i,1,1);
				$auxCategoria++;
			}
			if($i == 33){
				$this->categoria[$auxCategoria] =  $this->dados->val($i,1,1);
				$auxCategoria++;
			}
			if($i == 38){
				$this->categoria[$auxCategoria] =  $this->dados->val($i,1,1);
			}
		} 
		//loop que pega natureza do crime
		for($i=1,$auxNatureza=0; $i<$numeroLinhas; $i++){
			if(($i == 1)||($i == 5)||($i == 21)||($i == 27)||($i == 28)||($i == 31)||($i == 32)||($i == 37)||($i == 40)){
				continue;
			}
			else{	
				if($i>32){
					if($i<37){
						$this->natureza[$this->__getCategoria()[1]][$auxNatureza]= $this->dados->val($i,'B',1);
					}else{
						$this->natureza[$this->__getCategoria()[2]][$auxNatureza]= $this->dados->val($i,'B',1);
					}
				}else{
					if($i<32){
						$this->natureza[$this->__getCategoria()[0]][$auxNatureza]= $this->dados->val($i,'C',1);
					}else if($i>32 && $i<37){
						$this->natureza[$this->__getCategoria()[1]][$auxNatureza]= $this->dados->val($i,'C',1);
					}else{
						$this->natureza[$this->__getCategoria()[2]][$auxNatureza]= $this->dados->val($i,'C',1);
					}
				}
				$auxNatureza++;
			}
		}
		//loop que pega os anos disponiveis
		for($i=1,$auxTempo = 0; $i<$numeroColunas; $i++){
			if(($i == 1)||($i == 2)||($i == 3)){
				continue;
			}else{
				$this->tempo[$auxTempo] = $this->dados->val(1,$i,1);
				$auxTempo++;
			}
		}
		//loop que pega os dados do crime
		for($i=1,$auxLinha=0; $i<$numeroLinhas; $i++){	
			if(($i == 1)||($i == 5)||($i == 21)||($i == 27)||($i == 28)||($i == 31)||($i == 32)||($i == 37)||($i == 40)){
				continue;
			}else{	
				for($j=4,$auxColuna=0,$auxCategoria; $j<$numeroColunas; $j++){
						if($i<32){
							$auxCategoria = 0;
						}else if($i>32 && $i<37){
							$auxCategoria = 1;
						}else{
							$auxCategoria = 2;
						}
						$this->crime[$this->__getNatureza()[$this->__getCategoria()[$auxCategoria]][$auxLinha]][$this->__getTempo()[$auxColuna]] = $this->dados->raw($i,$j,1);
						$auxColuna++;
				}
				$auxLinha++;
			}
		}
		//print_r($this->__getCrime());
		$arrayCrime = $this->__getCrime();
		for($i=0,$arrayKey = $arrayCrime,$inicio = 0;$i<count($arrayCrime);$i++){
			$Natureza = key($arrayKey);
			$arrayTempo = $arrayCrime[$Natureza];
			for($j=0;$j<count(array_keys($arrayCrime[$Natureza]));$j++){
				echo $Natureza;
				echo " ";
				$Tempo = key($arrayTempo);
				echo $Tempo;
				echo " ";
				echo $arrayCrime[$Natureza][$Tempo];
				echo "<br>";
				next($arrayTempo);
			}	
			next($arrayKey);
		}
		/**
		//print_r($this->__getTempo());
		$arrayTempo = $this->__getTempo();
		for($i=0;$i<count($arrayTempo);$i++){
			echo $arrayTempo[$i];
			echo "<br>";
		}
		
		print_r(count($this->__getNatureza()));
		for($i=0,$array_keys = $this->__getNatureza(),$inicio = 0,$array = $this->__getNatureza();$i<3;$i++){
			$chave = key($array_keys);
			echo "=====.$chave";
			echo "<br>";
			for($j=$inicio;$j<(count($array[$chave])+$inicio);$j++){
				print_r($array[$chave][$j]);
				echo "<br>";
			}
			$inicio = $inicio+count($array[$chave]);
			next($array_keys);	
			
		}	
		/**
		for($i=0,$array = $this->__getCrime();$i<31;$i++){
			
			print_r(key($array));
			echo "&nbsp;";
			print_r(key($array[$this->__getNatureza()[$this->__getCategoria()[0]][1]]));
			echo "&nbsp;";
			print_r($array[$this->__getNatureza()[$this->__getCategoria()[0]][1]][$this->__getTempo()[1]]);
			echo "<br>";
			next($array);
		}**/
	}//fim do metodo parseDeSerieHistorica
	
	public function parsePorRegiao(){
		
	}
	
	public function parseDeQuadrimestre(){
		
	}
	
	public function __setNatureza($natureza){
		$this->natureza = $natureza;
	}
	
	public function __getNatureza(){
		return $this->natureza;
	}
	
	public function __setTempo($tempo){
		$this->tempo = $tempo;
	}
	
	public function __getTempo(){
		return $this->tempo;
	}
	
	public function __setCrime($crime){
		$this->crime = $crime;
	}
	
	public function __getCrime(){
		return $this->crime;
	}
	
	public function __setCategoria($categoria){
		$this->categoria = $categoria;
	}
	
	public function __getCategoria(){
		return $this->categoria;
	}

	public function somaLinhas($arrayCrime){
		$numeroLinhas = 31;
		$numeroColunas = 11;
		$soma;
		
		
		for($i=0;$i<$numeroLinhas;$i++){
			for($j=0;$j<$numeroColunas;$j++){
				$soma[$i] += $arrayCrime[$i][$j];				
			}
		}
		return $soma;
	}

	public function somaColunas($arrayCrime){
		$numeroLinhas = 31;
		$numeroColunas = 11;
		$soma;
	
	
		for($i=0;$i<$numeroColunas;$i++){
			for($j=0;$j<$numeroLinhas;$j++){
				$soma[$i] += $arrayCrime[$j][$i];
			}
		}
		return $soma;
	}
}