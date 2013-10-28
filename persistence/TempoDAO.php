<?php
include_once(__APP_PATH.'/model/Tempo.php');
include_once('Conexao.php');
class TempoDAO{
	private $conexao;
	public function __construct(){
		$this->conexao = new Conexao();
	} 
	
	public function listarTodos(){
		$sql = "SELECT * FROM tempo";
		$resultado = $this->conexao->banco->Execute($sql);
		while($registro = $resultado->FetchNextObject())
		{
			$dadosTempo = new Tempo();
			$dadosTempo->__constructOverload($registro->ID_TEMPO,$registro->INTERVALO);	
			$retornaTempos[] = $dadosTempo;
		}
		return $retornaTempos;
	} 
	public function listarTodasEmOrdem(){
		$sql = "SELECT * FROM tempo ORDER BY intervalo ASC ";
		$resultado = $this->conexao->banco->Execute($sql);
		while($registro = $resultado->FetchNextObject())
		{
			$dadosTempo = new Tempo();
			$dadosTempo->__constructOverload($registro->ID_TEMPO,$registro->INTERVALO);
			$retornaTempos[] = $dadosTempo;
		}
		return $retornaTempos;
	}
	public function consultarPorId($id){
		$sql = "SELECT * FROM tempo WHERE id_tempo = $id";
		$resultado = $this->conexao->banco->Execute($sql);
		$registro = $resultado->FetchNextObject();
		$dadosTempo = new Tempo();
		$dadosTempo->__constructOverload($registro->ID_TEMPO,$registro->INTERVALO);
		return $dadosTempo;
		
	}
	public function consultarPorIntervalo($intervalo){
		$sql = "SELECT * FROM tempo WHERE intervalo = $intervalo";
		$resultado = $this->conexao->banco->Execute($sql);
		$registro = $resultado->FetchNextObject();
		$dadosTempo = new Tempo();
		$dadosTempo->__constructOverload($registro->ID_TEMPO,$registro->INTERVALO);
		return $dadosTempo;
	}
	public function inserirTempo(Tempo $tempo){
		$sql = "INSERT INTO tempo (intervalo) VALUES ('{$tempo->__getIntervalo()}')";
		$this->conexao->banco->Execute($sql);
	}
}
