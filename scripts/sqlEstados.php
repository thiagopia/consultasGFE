<?php

$sql = "SELECT COUNT (*) AS QTDE
 ,numeroDia
 ,tipoNota
 ,tipoOperacao
 ,estado
 ,SUM(pesoTotal) AS pesoTotal
 ,SUM(faturamento) AS faturamento
 ,SUM(custoFrete) AS custoFrete
 /*(SUM(custoFrete)/SUM(pesoTotal)) AS custoKilo
 ,(SUM(custoFrete)/SUM(faturamento)) AS custoPorCento
 ,(SUM(faturamento)/SUM(pesoTotal)) AS precoMedio*/
 
 FROM (SELECT  
   GW1.GW1_DTEMIS AS numeroDia
   ,GW1.GW1_CDTPDC AS tipoNota
   ,GWN.GWN_CDTPOP AS tipoOperacao
   ,GU7.GU7_CDUF AS estado
   ,GWF.GWF_TPCALC
   ,pesoTotal = CASE WHEN GWF.GWF_TPCALC = '1' THEN SUM(GW8.GW8_PESOR) ELSE 0 END
   ,faturamento = CASE WHEN GWF.GWF_TPCALC = '1' THEN SUM(GW8.GW8_VALOR) ELSE 0 END
   ,SUM(GWM.GWM_VLFRET) AS custoFrete

  

 FROM GWM010 GWM

 INNER JOIN GW1010 GW1 ON 
 GW1.GW1_FILIAL  = GWM.GWM_FILIAL 
 AND GW1.GW1_CDTPDC  = GWM.GWM_CDTPDC 
 AND GW1.GW1_EMISDC  = GWM.GWM_EMISDC  
 AND GW1.GW1_SERDC   = GWM.GWM_SERDC  
 AND GW1.GW1_NRDC    = GWM.GWM_NRDC  
 AND GW1.D_E_L_E_T_ = ' ' 
 
 INNER JOIN GWN010 GWN ON 
 GWN.GWN_FILIAL =  GW1.GW1_FILIAL
 AND GWN.GWN_NRROM = GW1.GW1_NRROM
 AND GWN.D_E_L_E_T_ = ' ' 
 
 INNER JOIN GW8010 GW8 ON 
 GW8.GW8_FILIAL      = GWM.GWM_FILIAL
 AND GW8.GW8_CDTPDC  = GWM.GWM_CDTPDC 
 AND GW8.GW8_EMISDC  = GWM.GWM_EMISDC 
 AND GW8.GW8_SERDC   = GWM.GWM_SERDC  
 AND GW8.GW8_NRDC    = GWM.GWM_NRDC   
 AND GW8.GW8_ITEM    = GWM.GWM_ITEM   
 AND GW8.GW8_SEQ    = GWM.GWM_SEQGW8
 AND GW8.D_E_L_E_T_ = ' ' 

 LEFT JOIN GU3010 ON 
 GWM.GWM_CDTRP   = GU3010.GU3_CDEMIT   /* Nome do Transportador */


 INNER JOIN GU7010 ON 
 GW1.GW1_ENTNRC  = GU7010.GU7_NRCID    /* Nome da Cidade */

 LEFT JOIN GWF010 GWF ON 
      GWF.GWF_FILIAL   = GWM.GWM_FILIAL 
 AND GWF.GWF_NRCALC   = GWM.GWM_NRDOC 
 AND GWF.GWF_NRROM   = GWN.GWN_NRROM   
  --AND GWF.GWF_BAPICO   <> 0
 AND GWF.D_E_L_E_T_ = ' ' 

 INNER JOIN GU7010 GU7 ON
     GU7.GU7_NRCID = GW1.GW1_ENTNRC
 AND GU7.D_E_L_E_T_ = ' '
 WHERE  
      GWN.GWN_FILIAL  BETWEEN ('011') AND ('015') 
 AND GW1.GW1_DTEMIS BETWEEN ('20160901') AND ('20160907')
 AND GWN.D_E_L_E_T_ = ' ' 
 AND GW1.D_E_L_E_T_ = ' ' 
 AND GW8.D_E_L_E_T_ = ' ' 
 AND GWM.D_E_L_E_T_ = ' ' 
 AND GWM.GWM_CDESP  = ' '

 GROUP BY GW1.GW1_DTEMIS,GU7.GU7_CDUF, GW1.GW1_CDTPDC, GWN.GWN_CDTPOP, GWF.GWF_TPCALC
 ) tblTemp
 GROUP BY numeroDia, tipoNota, tipoOperacao, estado
 ORDER By numeroDia, tipoNota, tipoOperacao, estado";

$query = sqlsrv_query($conn, $sql);
$num = sqlsrv_num_rows($query);
$row = sqlsrv_fetch_array($query);

$sql2 = "SELECT numeroDia
 ,tipoNota
 ,tipoOperacao
 ,estado
 ,SUM(pesoTotal) AS pesoTotal
 ,SUM(faturamento) AS faturamento
 ,SUM(custoFrete) AS custoFrete
 /*(SUM(custoFrete)/SUM(pesoTotal)) AS custoKilo
 ,(SUM(custoFrete)/SUM(faturamento)) AS custoPorCento
 ,(SUM(faturamento)/SUM(pesoTotal)) AS precoMedio*/
 
 FROM (SELECT  
   GW1.GW1_DTEMIS AS numeroDia
   ,GW1.GW1_CDTPDC AS tipoNota
   ,GWN.GWN_CDTPOP AS tipoOperacao
   ,GU7.GU7_CDUF AS estado
   ,GWF.GWF_TPCALC
   ,pesoTotal = CASE WHEN GWF.GWF_TPCALC = '1' THEN SUM(GW8.GW8_PESOR) ELSE 0 END
   ,faturamento = CASE WHEN GWF.GWF_TPCALC = '1' THEN SUM(GW8.GW8_VALOR) ELSE 0 END
   ,SUM(GWM.GWM_VLFRET) AS custoFrete

  

 FROM GWM010 GWM

 INNER JOIN GW1010 GW1 ON 
 GW1.GW1_FILIAL  = GWM.GWM_FILIAL 
 AND GW1.GW1_CDTPDC  = GWM.GWM_CDTPDC 
 AND GW1.GW1_EMISDC  = GWM.GWM_EMISDC  
 AND GW1.GW1_SERDC   = GWM.GWM_SERDC  
 AND GW1.GW1_NRDC    = GWM.GWM_NRDC  
 AND GW1.D_E_L_E_T_ = ' ' 
 
 INNER JOIN GWN010 GWN ON 
 GWN.GWN_FILIAL =  GW1.GW1_FILIAL
 AND GWN.GWN_NRROM = GW1.GW1_NRROM
 AND GWN.D_E_L_E_T_ = ' ' 
 
 INNER JOIN GW8010 GW8 ON 
 GW8.GW8_FILIAL      = GWM.GWM_FILIAL
 AND GW8.GW8_CDTPDC  = GWM.GWM_CDTPDC 
 AND GW8.GW8_EMISDC  = GWM.GWM_EMISDC 
 AND GW8.GW8_SERDC   = GWM.GWM_SERDC  
 AND GW8.GW8_NRDC    = GWM.GWM_NRDC   
 AND GW8.GW8_ITEM    = GWM.GWM_ITEM   
 AND GW8.GW8_SEQ    = GWM.GWM_SEQGW8
 AND GW8.D_E_L_E_T_ = ' ' 

 LEFT JOIN GU3010 ON 
 GWM.GWM_CDTRP   = GU3010.GU3_CDEMIT   /* Nome do Transportador */


 INNER JOIN GU7010 ON 
 GW1.GW1_ENTNRC  = GU7010.GU7_NRCID    /* Nome da Cidade */

 LEFT JOIN GWF010 GWF ON 
      GWF.GWF_FILIAL   = GWM.GWM_FILIAL 
 AND GWF.GWF_NRCALC   = GWM.GWM_NRDOC 
 AND GWF.GWF_NRROM   = GWN.GWN_NRROM   
  --AND GWF.GWF_BAPICO   <> 0
 AND GWF.D_E_L_E_T_ = ' ' 

 INNER JOIN GU7010 GU7 ON
     GU7.GU7_NRCID = GW1.GW1_ENTNRC
 AND GU7.D_E_L_E_T_ = ' '
 WHERE  
      GWN.GWN_FILIAL  BETWEEN ('011') AND ('015') 
 AND GW1.GW1_DTEMIS BETWEEN ('20160901') AND ('20160907')
 AND GWN.D_E_L_E_T_ = ' ' 
 AND GW1.D_E_L_E_T_ = ' ' 
 AND GW8.D_E_L_E_T_ = ' ' 
 AND GWM.D_E_L_E_T_ = ' ' 
 AND GWM.GWM_CDESP  = ' '

 GROUP BY GW1.GW1_DTEMIS,GU7.GU7_CDUF, GW1.GW1_CDTPDC, GWN.GWN_CDTPOP, GWF.GWF_TPCALC
 ) tblTemp
 GROUP BY numeroDia, tipoNota, tipoOperacao, estado
 ORDER By numeroDia, tipoNota, tipoOperacao, estado";
$query2 = sqlsrv_query($conn, $sql2);

if($row['QTDE'] == 0) echo "<p id='semResultados'>Nenhum Resultado Encontrado!</p>";

for($i = 0; $i < $row['QTDE'];$i++) {

$row2 = sqlsrv_fetch_array($query2);

echo "<p>AQUI: ".$i."</p>";

}