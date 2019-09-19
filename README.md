# p7manager
#PASSANDO IL SAVE = 1 IL FILE VIENE SALVATO, 
#CON SAVE = 0, IL FILE VIENE SOLO VERIFICATO 
#RETURN TRUE SE VIENE VERIFICATO, 
#RETURN IL FILE DI DESTINAZIONE SE VIENE SALVATO, 
#RETURN FALSE SE NON VIENE VERIFICATO

#UTILIZZO:

$P7M = new P7Manager('/cartellaFileP7M', 'cartellaFilePdfEstratto');
$check = $P7M -> extract(nomeDelFileP7M(stringa), Save(int 1/0));

#esempio che oltra la verifica, salvarà il file sul server

$P7M = new P7Manager('/P7M', '/PDFESTRATTI');
$check = $P7M -> extract('miofile.pdf.p7m', 1);

