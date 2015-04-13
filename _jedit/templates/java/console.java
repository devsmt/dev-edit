package it.ev.test;
import java.io.Console;

public class ConsoleTest {
    public static void main(String[] args) {
        // Recuperiamo l'istanza di Console
        Console console = System.console();
        console.printf("Benvenuto alla console Java 1.6″);

        if (args.length>0){
            // Ora chiediamo un valore in input (notare il formato %s)
            String line = console.readLine("nPrego, %s, inserisci un testo >>", args[0]);

            console.printf("Echo: "+line.toUpperCase());

            char pwd[] = console.readPassword("nPrego, %s, inserisci il PIN >>", args[0]);

            if (pinEsatto(pwd)) {
                console.printf("Bene! PIN CORRETTO!");
            } else {
                //lo mostriamo giusto per vedere la rappresentazione
                //altrimenti non avrebbe senso
                console.printf("PIN ERRATO!");
            }
        } else {
            console.printf("nPrego inserire un argomento: java it.html.j16.ConsoleTest yourName");
        }
    }

    private static boolean pinEsatto(char[] pwd) {
        char pinEsatto[] = {'H','T','M','L','.','I','T'};
        //Usiamo l'utility della classe Arrays
        return java.util.Arrays.equals(pwd, pinEsatto);
    }
}
