<?php
/* Routing table:
** Indivual records write_indi
** Family records write_fam
** Event tags (individual, family or shared) write_ write_event
** Individual attribute tags write_attrib
** OBJE (media) write_media
** Source citations (SOUR) write_sour
** Source records (ZOUR) write_zour
** REPO records (not REPO tags) write_repo
** _PLAC records /tags (a unique RM construct) write_place
*/
$route_table = array(
      'HEAD' => 16,
      'INDI' => 1,
      'FAM'  => 2,
      'BIRT' => 3,
      'CHR'  => 3,
      'DEAT' => 3,
      'BURI' => 3,
      'CREM' => 3,
      'ADOP' => 3,
      'BAPM' => 3,
      'BARM' => 3,
      'BASM' => 3,
      'BLES' => 3,
      'CHRA' => 3,
      'CONF' => 3,
      'FCOM' => 3,
      'ORDN' => 3,
      'NATU' => 3,
      'EMIG' => 3,
      'IMMI' => 3,
      'CENS' => 3,
      'PROB' => 3,
      'WILL' => 3,
      'GRAD' => 3,
      'RETI' => 3,
      'EVEN' => 3,
      'PROB' => 3,
      'ANUL' => 3,
      'DIV'  => 3,
      'DIVF' => 3,
      'ENGA' => 3,
      'MARB' => 3,
      'MARC' => 3,
      'MARR' => 3,      
      'MARL' => 3,
      'MARS' => 3,
      'CAST' => 4,
      'DSCR' => 4,
      'EDUC' => 4,
      'IDNO' => 4,
      'NATI' => 4,
      'NCHI' => 4,
      'NMR'  => 4,
      'OCCU' => 4,
      'PROP' => 4,
      'RELI' => 4,
      'SSN'  => 4,
      'TITL' => 4,
      'FACT' => 4,
      'OBJE' => 5,
      'ZOUR' => 6,
      'SOUR' => 7,
      'FAMS' => 8,
      'CHIL' => 9,
      'ZPLAC' => 10,
      'PLAC' => 10,
      'ZREPO' => 11,
      'ZNOTE' => 12,
      'ZOBJE' => 13,
      'NOTE' => 14,
      'FILE' => 15,
      'REFN' => 17
                     );
      
?>