      *==========================================================================
      * SCOPO DEL PROGRAMMA
      *==========================================================================
     H DECEDIT('0,') DATEDIT(*YMD/)
     H option(*srcstmt : *nodebugio)
     H*dftactgrp(*no) bnddir('AW')
      *==========================================================================
      * CONST
      *==========================================================================
     D FQ              C                   CONST('''')
      * numero di righe da restituire
     D NUM_ROWS        C                   CONST(50)


      *==========================================================================
      * INPUT
      *
     D input           DS
     D  i_cliID                       6a
      *--------------------------------------------------------------------------
      * OUTPUT
      *--------------------------------------------------------------------------
     D output          DS
     D  o_nrows                       2s 0
     D  o_nr_tot                      5s 0
     D  o_errcode                     9a
     D  o_errstate                    9a
     D  o_errmsg                    900a
      * array contenti i dati selezionati suddivisi per colonne
     D  o_NUORD                       5s 0 dim(NUM_ROWS)
     D  o_ANORD                       4s 0 dim(NUM_ROWS)
     D  o_DAORD                      10a   dim(NUM_ROWS)
     D  o_COAGE                       3a   dim(NUM_ROWS)
     D  o_COCLI                       6A   dim(NUM_ROWS)
     D  o_TFLGOR                      1a   dim(NUM_ROWS)
      * ana cliente
     D  o_RAGCL                      35a   dim(NUM_ROWS)
     D  o_LOCCL                      20a   dim(NUM_ROWS)
      *-----------------------------------------------------------------------
      * stato di uscita del programma
     D errore          S              1a



      * -----------------------------------------------------------------------
     D set_error       PR
     D    msg                        80a   VALUE




      *==========================================================================
      * var private (prefisso $)
      *==========================================================================
     D $sql_select     S          32000a
      * offset da cui incominciare a fare il fetch
     D $pos            S              2s 0 inz(1)
     D $i              S              5s 0 inz(0)
     D $cliID          S                   like(i_cliID)



      * num record letti dalla select
     D $n_rec_read     S              5s 0


      * RecordSet contenente i dati restituiti da $sql_select
     D $rsOrd          DS                  OCCURS(NUM_ROWS)
     D  TNUORD                        5s 0
     D  TANORD                        4s 0
     D  TDAORD                       10a
     D  TCOAGE                        3a
     D  TCOCLI                        6A
     D  TRIFPC                        1a
      * anagrafica
     D  RASCL                        35a
     D  LOCCL                        20a

      *==========================================================================
      * PARAMETRI DI I/O
      *
     C     *entry        plist
     C                   parm                    errore
     C                   parm                    input
     C                   parm                    output
      *==========================================================================
      * MAIN
      *==========================================================================

      * int SQL flags
     C/exec sql
     C+   Set Option Commit =*NONE,
     C+   CloSQLCsr =*ENDMOD,
     C+   DatFmt =*ISO,
     C+   TimFmt =*ISO,
     C+   SqlPath=*LIBL
     C/END-EXEC


      /free
        // main()
        exsr inzsr;
        exsr ricerca;
        *inlr=*on;
        return;


        // inizializza le variabili e le strutture dati
        begsr inzsr;

            // init vars
            clear output;

            // inizializza/valida i dati di input e copia in var locali per non modificare l'input
            $cliID = i_cliID;

            // TODO: if cpage < 1 errore: il valore minimo per page è 1

            // output uguale all'input
            o_nrows = NUM_ROWS;
            //o_cliID = i_cliID;

        endsr;


        // esegue una selezione sul DB
        begsr ricerca;

            exsr sql_build;
            exsr sql_select_open;

            exsr fetch_to_output;

            exec sql close C1;
       endsr;

       // crea SQL di selezione
       begsr sql_build;

         $sql_select = 'SELECT '+
             ' TNUORD, TANORD, TDAORD, TCOAGE, '+
             ' TCOCLI, TRIFPC, '+
             // da anagrafica
             ' char(0) RASCL, char(0) LOCCL '+
             'FROM INTCT00F T'+
             ' WHERE ' +
             '   TTIPRE <> '+FQ+'A'+FQ+' and ' +
             '   trim(TCOCLI) = '+FQ+%trim($cliID)+FQ;
           $sql_select = %trim($sql_select);
       endsr;

       // apre il cursore e riempie la DS $rsOrd con NUM_ROWS records
       begsr sql_select_open;
           exec sql prepare S3 FROM :$sql_select;
           if SqlCod<>0;
             errore = *on;
             o_errcode = %char(sqlcod);
             o_errstate = sqlstt;
             o_errmsg = 'PREPARE:'+$sql_select;
             leavesr;
           endif;

           exec sql DECLARE C1 INSENSITIVE SCROLL CURSOR FOR S3;
           exec sql OPEN C1;
           if SqlCod<>0;
             errore = *on;
             o_errcode = %char(sqlcod);
             o_errstate = sqlstt;
             o_errmsg = 'OPEN:'+$sql_select;
             leavesr;
           endif;


           exec sql get diagnostics :o_nr_tot = DB2_NUMBER_ROWS;
           if o_nr_tot > 0;
             // ok
           else;
             // errore = *on;
             // nessun record trovato, segnalato in o_nr_tot
             o_errcode = '0';
             o_errstate = '0';
             o_errmsg = 'no records found for criteria '+
                 ' CDCLI='+%trim($cliID);
             leavesr;
           endif;

           $pos = 1;
           exec sql FETCH RELATIVE :$pos FROM C1 FOR :o_nrows ROWS INTO :$rsOrd;

           if SqlCod<>0;
             errore = *on;
             o_errcode = %char(sqlcod);
             o_errstate = sqlstt;
             o_errmsg = 'FETCH('+ %char($pos) +','+ %char(o_nrows) +'):';
             o_errmsg = o_errmsg + $sql_select;
             leavesr;
           endif;


           // contiene il numero di righe lette
           //$n_rec_read = SqlErrd(3);   //contiene il numero di righe lette
           exec sql get diagnostics :$n_rec_read = ROW_COUNT;
           if $n_rec_read <= 0;
             o_errmsg = '0 rec found';
             leavesr;
           endif;
       endsr;

       begsr fetch_to_output;

           for $i=1 to $n_rec_read;
                // sposta il puntatore interno a RS sul record $i
                 %OCCUR($rsOrd) = $i;
                // copia dati
                o_NUORD($i)  = TNUORD;
                o_ANORD($i)  = TANORD;
                o_DAORD($i)  = TDAORD;
                o_COAGE($i)  = TCOAGE;
                o_COCLI($i)  = TCOCLI;
                o_TFLGOR($i) = TRIFPC;
                // da anagrafica
                o_RAGCL($i)  = RASCL;
                o_LOCCL($i)  = LOCCL;
           endfor;

           // numero righe lette
           O_NROWS = $n_rec_read;
       endsr;



        //--------------------------------------------------------------------
        // routine di log/passaggio degli errori
        // USO: set_error('error str')
      /end-free

     P set_error       B
     D set_error       PI
     D    msg                        80a   VALUE
     D*Result          S             10I 0
      /free
       errore = '1';
       if o_errn < %elem(o_err);
           // setta errore in output
           o_err(o_errn) = msg;
           // setta indice errore al prossimo
           o_errn += 1;
       else;
           o_err(o_errn) = 'max error number reached!';
       endif;
      /end-free
     P                 E

      *-----------------------------------------------------------------
      *   init error handling subroutine
     C     *PSSR         BEGSR
     C                   eval      errore=*on
     C                   eval      *inlr=*on
     C                   return
     C                   ENDSR









