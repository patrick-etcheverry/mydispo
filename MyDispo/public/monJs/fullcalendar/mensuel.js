

document.addEventListener('DOMContentLoaded', function() {

  const creneauObjet = {
    start: "",
    end: "",
    title: "",
    type: "",
    prio: "",
    enseignant: "",
  }

  var mensuelEl = document.getElementById('mensuel');
  var mensuel = new FullCalendar.Calendar(mensuelEl, {
    plugins: [
      'interaction', 'dayGrid'
    ],
    selectable: true,
    displayEventTime : false,
    editable: true,
    eventDurationEditable: false,
    height: 700,
    events: eventsMensuel,
    contentHeight: 'auto',
    locale: 'fr',
    selectOverlap: false,
    select: function(arg) {

      closeNav();
      var title = prompt('Titre du créneau:');
      if (title) { // si un titre d'événement a été saisi
        mensuel.addEvent({title: title, start: arg.start, end: arg.end, allDay: true, classNames: ['plusBord']})
      }

      mensuel.unselect();
      mensuel.getEvents().forEach(event => {
        event.setProp("borderColor", "white");
      });
    },
    eventRender: function(info) {
      if(info.event.rendering == 'background'){
        info.el.append(info.event.title);
        info.el.style.color = "white";
        info.el.style.fontSize = "12px";
      }

      var dateDeb = mensuel.formatDate(info.event.start, {
        weekday: 'long',
        day: 'numeric',
        year: 'numeric',
        month: 'long',
        locale: 'fr'
      });
      var dateFin = mensuel.formatDate(info.event.end, {
        weekday: 'long',
        day: 'numeric',
        year: 'numeric',
        month: 'long',
        locale: 'fr'
      });
      var contenu = "Titre : " + info.event.title + "</br>Début : " + dateDeb + "</br>Fin : " + dateFin;

    },
    eventClick: function(info) {
      if(info.event.rendering != "background"){
      mensuel.getEvents().forEach(event => {
        event.setProp("borderColor", "white");
      });
      document.getElementById('texteExplicatif').innerHTML="Merci de préciser dans le descriptif les horaires pour lesquels vous n'êtes pas disponible.";
      document.getElementById('texteExplicatif').style.display="block";

      if(saisieEnseignant == false){
      document.getElementById('texteExplicatif').style.display="none";
    }
      document.getElementById('type').style.display="none";
      document.getElementById('prio').style.display="none";
      document.getElementById('titrevt').style.display="block";
      document.getElementById('nomcreneau').style.display="block";
      document.getElementById('apply').style.display="block";
      document.getElementById('dateDebut').innerHTML = "";
      document.getElementById('dateFin').innerHTML =  "";
      document.getElementById('titrevt').value = info.event.title;
      info.event.setProp("borderColor", "red");

      document.getElementById('apply').onclick = function() {
        if (document.getElementById('titrevt').value != '') {
          info.event.setProp("title", document.getElementById('titrevt').value);
          document.getElementById("nomcreneau").innerHTML = info.event.title;
          mensuel.rerenderEvents();
        }

      };
      document.getElementById('remove').onclick = function() {
        if (confirm("Voulez vous vraiment supprimer ce créneau ?")) {
          info.event.remove();
        }
        closeNav();
      };
      document.getElementById('close').onclick = function() {
        closeNav();
        info.event.setProp("borderColor", "white");
      };
      openNav();
    }
}
  });






  document.getElementById('submit2').onclick = function() {


    if(saisieEnseignant){
    var deltaRemarquePonctu = [];
    var deltaCreneauxPonctu = [];
    var compteurEventsMensuel = 0;
    // récup toutes les infos de l’enseignant saisies dans le formulaire

    creneauxSaisie = mensuel.getEvents();   // Les créneaux
    remarquePonctuSaisie = document.getElementById('remarquesPonctu').value; // Remarque ponctu

    // récup toutes les infos de l’enseignant en BD

    // Events mensuels -> eventsMensuel
    // Remarque ponctu -> remarquePonctu


    // Calculer le delta pour enregistrer dans le log

    // Delta sur les remarques
    if(!(remarquePonctuSaisie == remarquePonctu)){

    if(remarquePonctuSaisie == "" && remarquePonctu != ""){
    deltaRemarquePonctu.push("Suppression de la remarque sur les contraintes professionnelles ponctuelles");
  }
  else if (remarquePonctuSaisie != "" && remarquePonctu == ""){
  deltaRemarquePonctu.push("Ajout de la remarque sur les contraintes professionnelles ponctuelles");
}
else {
deltaRemarquePonctu.push("Modification de la remarque sur les contraintes professionnelles ponctuelles");
}
}


//Delta sur les créneaux
if(eventsMensuel.length > creneauxSaisie.length){
deltaCreneauxPonctu.push("Suppression de créneaux professionnels ponctuels");
}
if(eventsMensuel.length < creneauxSaisie.length){
deltaCreneauxPonctu.push("Ajout de créneaux professionnels ponctuels");
}



//Envoie des logs à LogEnseignantController
if(deltaRemarquePonctu.length == 0 && deltaCreneauxPonctu.length == 0){
envoyerLogPonctu("Aucune modif remarque", "Aucune modif créneau", enseignant);
}
else if(deltaRemarquePonctu.length == 0){
envoyerLogPonctu("Aucune modif remarque", deltaCreneauxPonctu, enseignant);
}
else if (deltaCreneauxPonctu.length == 0){
envoyerLogPonctu(deltaRemarquePonctu, "Aucune modif créneau", enseignant);
}
else{
envoyerLogPonctu(deltaRemarquePonctu, deltaCreneauxPonctu, enseignant);
}

}



















// Effacer les données de l’enseignant en BD et envoyer les données du formulaire de l’enseignant en BD

if (saisieEnseignant) {
  supprimerDesCreneaux("ContrainteProPonctu", enseignant);

  var tableauCreneaux = [];
  creneaux = mensuel.getEvents();
  creneaux.forEach(function(creneau){
    if(creneau.extendedProps.type != 'Evenement'){
      var aAjouterAuTableau = Object.create(creneauObjet);
      aAjouterAuTableau.start = creneau.start.toISOString();
      aAjouterAuTableau.end = creneau.end.toISOString();
      aAjouterAuTableau.title = creneau.title;
      aAjouterAuTableau.type = "ContrainteProPonctu";
      aAjouterAuTableau.prio = "sansPrio";
      aAjouterAuTableau.enseignant = enseignant;
      tableauCreneaux.push(aAjouterAuTableau);
    }
  });
  enregistrerDesCreneaux(tableauCreneaux);
}


if(saisieEnseignant == false){
  supprimerDesCreneaux("Evenement","");

  var tableauCreneaux = [];
  creneaux = mensuel.getEvents();
  creneaux.forEach(function(creneau){
    var aAjouterAuTableau = Object.create(creneauObjet);
    aAjouterAuTableau.start = creneau.start.toISOString();
    aAjouterAuTableau.end = creneau.end.toISOString();
    aAjouterAuTableau.title = creneau.title;
    aAjouterAuTableau.type = "Evenement";
    aAjouterAuTableau.prio = "sansPrio";
    tableauCreneaux.push(aAjouterAuTableau);
  });
  enregistrerDesCreneaux(tableauCreneaux);
}
}



mensuel.render();

});
