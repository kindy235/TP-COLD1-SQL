#include <Arduino.h>
#include <WiFi.h>

//Constants pour les pins où le capteur de lumiere et la LED sont branchés.
const char *ssid = "ssid";
const char *password = "ssid";
const char *host = "host";
const int ledPin = 5;

// Variable contenant L'ID du capteur
const int idCapteur = 2020;

// Offset est la valeur maximale lue quant la lumunosite est au max
// NB à calibrer selon l'endroit
int offset = 1000;

// On stockera la valeur de la limosite dans cette variable
int valeur = 0;

//Définition d'une variable globale pour stocker le niveau de lumière
int intensiteLED;

void setup()
{
    Serial.begin(115200);
    Serial.println("Capteur de lumunosité");

    // Configuration en mode sortie du PIN 5 pour la LED
    pinMode(ledPin, OUTPUT);

    // Connexion au reseau wifi
    Serial.println();
    Serial.println();
    Serial.print("Connexion au SSID: ");
    Serial.println(ssid);
    WiFi.begin(ssid, password);

    while (WiFi.status() != WL_CONNECTED)
    {
        delay(500);
        Serial.print(".");
    }
    Serial.println("");
    Serial.println("Connexion réussie !!");
    Serial.println("Adresse IP : ");
    Serial.println(WiFi.localIP());
}

void loop()
{
    // Lit la valeur de l'entrée Analogique A0
    valeur = analogRead(A0);

    // Sert a allumer ou à etiendre la LED en fonction de la lumosite
    // Elle restera alummée lorsqu'il n'ya pas de lumiere
    intensiteLED = 1023 - valeur;
    intensiteLED -= offset;

    if (intensiteLED < 0)
    {
        intensiteLED = 0;
    }
    Serial.print("Lumunosite = ");
    Serial.println(valeur);
    Serial.print("IntensiteLeD = ");
    Serial.println(intensiteLED);

    // Allume ou etient la LED quand la photoresistance detecte de la lumière
    digitalWrite(ledPin, intensiteLED);

    Serial.println("Connexion à l'hote : ");
    Serial.println(host);

    // // Creation du client HTTP
    WiFiClient client;
    const int httport = 80;

    if (!client.connect(host, httport))
    {
        Serial.println("La connexion a echoué");
        return;
    }

    // Envoie de la requete au serveur
    client.print(String("GET http://192.168.0.24/insert.php?") +
                 ("&idCapteur=") + idCapteur +
                 ("&valeur=") + valeur +
                 " HTTP/1.1\r\n" +
                 "Host: " + host + "\r\n" +
                 "Connection: close\r\n\r\n");
    unsigned long timeout = millis();

    while (client.available() == 0)
    {
        if (millis() - timeout > 1000)
        {
            Serial.println(">>> Client timeout !");
            client.stop();
            return;
        }
    }

    // Lire toute la reponse du serveur et l'afficher sur le moniteur serie
    while (client.available())
    {
        String line = client.readStringUntil('\r');
        Serial.print(line);
    }
    Serial.println();
    Serial.println("Fermeture de la connexion");

    // Delai d'attente en millisecondes pour envoyer une nouvelle mesure
    delay(10000);
}
