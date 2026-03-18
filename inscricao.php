<?php

// ============================================
// BLOCO 1 — RECEBER OS DADOS DO FORMULÁRIO
// ============================================
// $_POST contém tudo que veio do formulário com method="POST"

$nome        = $_POST['nome'];
$estado      = $_POST['estado'];
$cidade      = $_POST['cidade'];
$whatsapp    = $_POST['whatsapp'];
$experiencia = $_POST['experiencia'];
$atuacao     = $_POST['atuacao'];
$ultimoCurso = $_POST['ultimo-curso'];
$traducao    = $_POST['traducao'];
$comoSoube   = $_POST['como-soube'];

// O checkbox vem como array — tratamos separado
// Se nenhuma opção foi marcada, usamos array vazio como padrão
$frustracao = isset($_POST['frustracao']) ? $_POST['frustracao'] : [];


// ============================================
// BLOCO 2 — TRATAR OS DADOS
// ============================================
// Transforma o array de frustrações em texto
// ["durabilidade", "acabamento"] → "durabilidade, acabamento"

$frustracaoTexto = implode(", ", $frustracao);


// ============================================
// BLOCO 3 — ENVIAR PARA O GOOGLE SHEETS
// ============================================

$url = "https://script.google.com/macros/s/AKfycbxKr2K5rf6KRrSUlGdibqjBNDSSm1RX_Ijbp3f2cL6Mnr7XGTNZRcrNeDNBZ-RrosOJ/exec";

// Monta o objeto com todos os dados para enviar
$dados = json_encode([
    "nome"        => $nome,
    "estado"      => $estado,
    "cidade"      => $cidade,
    "whatsapp"    => $whatsapp,
    "experiencia" => $experiencia,
    "atuacao"     => $atuacao,
    "ultimo_curso"=> $ultimoCurso,
    "frustracao"  => $frustracaoTexto,
    "traducao"    => $traducao,
    "como_soube"  => $comoSoube
]);

// curl envia os dados para a URL do Apps Script
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $dados);
curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);  // segue redirecionamentos do Google
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // evita erro de SSL em hospedagem gratuita
$resposta = curl_exec($curl);
curl_close($curl);


// ============================================
// BLOCO 4 — REDIRECIONAR PARA OBRIGADO.HTML
// ============================================
// Após salvar, manda a visitante para a página de confirmação

header("Location: obrigado.html");
exit;

?>