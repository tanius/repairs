{*
Copyright 2011-2017 Nick Korbel

This file is part of Booked Scheduler.

Booked Scheduler is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

Booked Scheduler is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Booked Scheduler.  If not, see <http://www.gnu.org/licenses/>.
*}
{include file='globalheader.tpl'}
<h1>REPAIRS Hilfe</h1>

<div id="help">


<h2>Registrierung / Konto hinzufügen</h2>

<p>Um REPAIRS verwenden zu können, ist ein Benutzerkonto erforderlich. Selbständige Registrierung neuer Konten ist in REPAIRS deaktiviert. Um ein neues Benuzterkonto anzulegen, muss ein Anwendungs-Admin das Formular unter "Admin → Benutzer → … → Benutzer hinzufügen" ausfüllen.</p>

<p>Nachdem Ihr Konto registriert wurde, können Sie sich anmelden und haben Zugriff auf alle Ressourcen, die für Sie freigegeben wurden.</p>


<h2>Werkstattaufträge</h2>

<h3>Werkstattauftrag anlegen</h3>

<p>Ein Werkstattauftrag ist eine Reservierung eines Fahrzeugs oder einer anderen Ressource, so dass diese im gebuchten Zeitraum nicht im normalen Betriebsablauf zur Verfügung steht. Darüber hinaus speichert ein Werkstattauftrag welche Ersatzteile benötigt wurden, ggf. anfallende Dokumente, und ob der Auftrag bereits ausgeführt ist.</p>

<ol>
  <li>Wählen Sie einen Werkstattplan unter dem Menüpunkt "Werkstattpläne".</li>
  <li>Navigieren Sie zum richtigen Monat.</li>
  <li>Klicken Sie mit der linken Maustaste auf den Tag, an dem Sie den Werkstattauftrag anlegen wollen.</li>
  <li>Wählen Sie "Werkstattauftrag anlegen".</li>
  <li>Klicken Sie auf "Ändern" hinter "Ressourcen" um das richtige Fahrzeug oder sonstige Ressource auszuwählen. Sie können mehrere Ressourcen gleichzeitig auswählen.</li>
  <li>Klicken Sie auf "Hinzufügen" hinter "Ersatzteile" um benötigte Ersatzteile hinzuzufügen.</li>
  <li>Füllen Sie die restlichen Felder aus und klicken Sie anschließend auf "Anlegen".</li>
</ol>

<p>Sie können nur Werkstattaufträge für Ressourcen anlegen, die für Sie von einem Ressourcen-Admin freigegeben wurden.</p>

<p>Falls für Ihren Benutzer eingestellt wurde dass Ihre angelegten Werkstattaufträge bestätigt werden müssen, so wird Ihnen das nach Klick auf "Anlegen" für den angelegten Auftrag mitgeteilt.</p>

<p>Alle Änderungen an einer Reservierung werden erst wirksam, wenn Sie die Buchung speichern in dem Sie auf "Update" drücken.</p>

<p>Nur Anwendungsadministratoren können Reservierungen in der Vergangenheit eintragen.</p>


<h3>Werkstattauftrag auf "ausgeführt" setzen</h3>

<p>Ausgeführte Werkstattaufträge werden im Kalender in grün angezeigt und können so leicht von noch anstehenden Aufträgen unterschieden werden. Außerdem beeinflusst es die Ersatzteil-Lagerhaltung, einen Werkstattauftrag auf "ausgeführt" zu setzen.</p>

<p>Einen Werkstattauftrag auf "ausgeführt" zu setzen ist nur für dafür privilegierte einzelne Benutzer möglich. Ein Anwendungs-Admin kann weitere Benutzer dafür unter "Admin → Benutzerdefinierte Attribute" freischalten.</p>

<p>Um einen Werkstattauftrag auf "ausgeführt" zu setzen:</p>

<ol>
  <li>Klicken Sie auf den Werkstattauftrag um ihn zum Bearbeiten zu öffnen.</li>
  <li>Wenn kein Feld "Zusätzliche Attribute: Werkstattauftrag ausgeführt" angezeigt wird, so übertragen Sie zuerst den Werkstattauftrag an Ihren eigenen Benutzer. (Wie gesagt wird das Feld nicht für alle Benutzer gesammelt.) Klicken Sie dazu auf "Ändern" oben links hinter dem Namen des Benutzers, der den Werkstattauftrag angelegt hat.</li>
  <li>Klicken Sie nun auf "Werkstattauftrag ausgeführt" um einen Haken in dieses Formularfeld zu setzen.</li>
  <li>Klicken Sie abschließend oben rechts auf "Update" um die Änderungen zu speichern.</li>
</ol>


<h3>Wiederkehrende Werkstattaufträge</h3>

<p>Werkstattaufträge können automatisch wiederholt werden. Für die Wiederholung gibt es eine Reihe von verschieden Möglichkeiten. Alle Wiederholungsoptionen gelten inklusive des Bis-Datums.</p>

<p>Die Optionen für die Wiederholung ermöglichen flexible Wiederholungsmöglichkeiten. Zum Beispiel:</p>

<ul>
  <li>"Täglich alle 2 Tage" erstellt Reservierungen für jeden zweiten Tag für Ihre angegebene Zeit.</li>
  <li>"Wöchentlich, jede 1. Woche am Montag, Mittwoch, Freitag" erstellt eine Reservierung an jedem dieser Tage in jeder Woche für Ihre angegebene Zeit.</li>
  <li>"Monatlich, jeden 3. Monat am 15. des Monats" ist genauso möglich wie "Monatlich, jeden 3. Monat am 3. Samstag des Monats".</li>
</ul>


<h3>Weitere Teilnehmer</h3>

<p>Mit dem Feld "Teilnehmerliste" können Sie auch weitere Teilnehmer zu jeder Reservierung hinzufügen bzw. einladen. Dies ist dazu gedacht, Personen zu informieren für die ein Werkstattauftrag außerdem relevant ist. Zum Beispiel Werkstattmitarbeiter, die daran arbeiten sollen.</p>

<p>Hinzugefügte Teilnehmer werden in der Teilnehmerliste der Reservierung eingetragen und erhalten eine Email.</p>


<h3>Zubehör</h3>

<p>Ersatzteile können einem Werkstattauftrag zugeordnet werden. Zu jedem Werkstattauftrag können nur solche Ersatzteile zugeordnet werden, die mit den gebuchten Fahrzeugen / sonstigen Ressourcen kompatibel sind.</p>

<p>Die Ersatzteil-Verwaltung dient der Lagerhaltung. Jeder Werkstattauftrag bei dem "Werkstattauftrag ausgeführt" gesetzt ist 
"verbraucht" die diesem zugeordneten Ersatzteile. Legt man nun einen Werkstattauftrag an, ist der verfügbare Lagerbestand des Ersatzteils entsprechend geringer. Wird der Lagerbestand zu gering, kann rechtzeitig nachbestellt werden.</p>

<p>Dieses System "Ersatzteilverbrauch bei Ausführung des Werkstattauftrags" bedeutet aber auch, dass Ersatzteile durch geplante Werkstattaufträge <b>nicht reserviert werden</b> können. Außerdem dürfen ausgeführte Werkstattaufträge <b>keinesfalls gelöscht werden</b>, weil dies die darin "verbrauchten" Ersatzteile dann wieder dem Lagerbestand zurechnen würde.</p>


<h3>Werkstattaufträge im Namen anderer</h3>

<p>Anwendungs-Administratoren und Gruppen-Administratoren können Werkstattaufträge für andere Nutzer anlegen, 
indem Sie im Formular auf den Link "Ändern" rechts neben dem Namen des Benutzers klicken.</p>


<h3>Aktualisieren eines Werkstattauftrags</h3>

<p>Sie können jeden Werkstattauftrag den Sie erstellt haben oder der auf Ihrem Namen erstellt wurde, ändern.</p>

<p>Anwendungs-Administratoren und Gruppen-Administratoren können auch Reservierungen anderer Benutzer ändern und löschen.</p>

<p>Nur Anwendungsadministratoren können Reservierungen in der Vergangenheit verändern.</p>


<h3>Aktualisieren von wiederholten Werkstattaufträgen</h3>

<p>Wenn eine Buchung mit Wiederholung erstellt wird, dann handelt es sich um einer Serienreservierung. Nachdem Sie Änderungen vorgenommen haben und die Reservierung aktualisieren möchten, werden Sie aufgefordert, die Instanzen der Serie auszuwählen, für die Sie die Änderungen vornehmen möchten. Sie haben verschiedene Auswahlmöglichkeiten.</p>

<ul>
  <li>Sie können Ihre Änderungen an der Instanz anwenden, die Sie gerade sehen (nur an dieser Instanz) und alle anderen Instanzen werden nicht verändert.</li>
  <li>Sie können Ihre Änderungen an allen Instanzen anwenden, die noch nicht abgelaufen sind.</li>
  <li>Sie können Ihre Änderungen an allen zukünftigen Instanzen ab der und inklusive der gerade betrachteten Instanz durchführen lassen.</li>
</ul>


<h3>Löschen eines Werkstattauftrags</h3>

<p>Das löschen eines Werkstattauftrags entfernt diesen komplett aus dem Werkstattplan. Er wird in REPAIRS dann nirgends mehr sichtbar sein.</p>


<h3>Löschen von wiederholten Werkstattaufträgen</h3>

<p>Ähnlich wie bei der "Aktualisierung von wiederholten Werkstattaufträgen" (siehe oben) können Sie beim Löschen auswählen, welche Instanzen Sie löschen möchten.</p>


<h3>Hinzufügen eines Werkstattauftrags zu einem eigenen Kalender-Programmm</h3>

<p>Beim Betrachten oder Aktualisieren eines Werkstattauftrags finden Sie einen Eintrag "Mehr → Zum Kalender hinzufügen". Wenn Sie diesen Eintrag auswählen und auf Ihrem Computer ein Kalender-Programm wie Outlook installiert ist, werden Sie gefragt ob Sie den Kalendereintrag importieren möchten. Wenn kein bekanntes Kalenderprogramm installiert ist, werden Sie aufgefordert, eine ICS-Datei herunterzuladen. Kalender-Programme sind zum Beispiel Outlook®, iCal, Mozilla Lightning, Evolution.</p>

<p>ICS ist ein Standard-Kalender-Format. Sie können diese Datei verwenden, um die Reservierung in jede Anwendung zu importieren, die das iCalendar-Format unterstützt.</p>


<h2>Abonnieren von Werkstattplänen</h2>

<h3>Einen Werkstattplan oder Ressourcen-Kalender abonnieren</h3>

<p>Zusätzlich zur oben beschriebenen Möglichkeit, einzelne Werkstattaufträge zu Ihrem Kalender-Programm hinzuzufügen, ist dies auch mit kompletten Werkstattplänen automatisiert möglich.</p>

<p>Werkstattpläne und Kalender einzelner Ressourcen können bei Bedarf veröffentlicht und abonniert werden. Damit dies funktioniert, muss der Administrator einen Subscription-Schlüssel in der Konfigurationsdatei konfiguriert haben. Um Werkstattpläne oder Ressourcen-Kalender-Abonnements zu aktivieren, schalten Sie einfach "Abonnements" bei der Verwaltung des Werkstattplans oder der Ressource ein.</p>

<p>Um einen Werkstattplan zu abonnieren, öffnen Sie den Werkstattplan. Auf der rechten Seite oberhalb des Kalenders finden Sie dann den Link "Kalender abonnieren". (Dieser Link ist aktuell in REPAIRS versteckt, kann aber von einem Administrator durch CSS bei Bedarf wieder sichbar gemacht werden.)</p>


<h3>Kalender Programme (Outlook&reg;, iCal, Mozilla Lightning, Evolution)</h3>

<p>In den meisten Fällen genügt ein Klick auf "Kalender abonnieren", um automatisch ein Abonnement in Ihrem Kalender-Programm einzurichten. Falls Outlook es nicht automatisch hinzufügt, öffnen Sie die Kalenderansicht, dann rechts auf Meine Kalender und wählen Sie "Hinzufügen → Kalender → Aus dem Internet". Fügen Sie die URL ein, die Sie unter "Kalender Abonnieren" in REPAIRS angezeigt finden.</p>


<h3>Google&reg; Kalender</h3>

<ol>
  <li>Öffnen Sie die Google "Kalender-Einstellungen".</li>
  <li>Klicken Sie auf die Registerkarte "Kalender".</li>
  <li>Klicken Sie auf "In interessanten Kalendern suchen".</li>
  <li>Klicken Sie auf "Über URL hinzufügen".</li>
  <li>Fügen Sie die URL ein, die Sie unter "Kalender Abonnieren" in Booked Schedule gedruckt finden.</li>
</ol>

</div>

{include file='globalfooter.tpl'}
