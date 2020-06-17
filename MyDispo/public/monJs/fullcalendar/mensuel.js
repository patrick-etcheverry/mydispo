

document.addEventListener('DOMContentLoaded', function() {
  var mensuelEl = document.getElementById('mensuel');
  var mensuel = new FullCalendar.Calendar(mensuelEl, {
    plugins: [
      'interaction', 'dayGrid'
    ],
    selectable: true,
    displayEventTime : false,
    editable: true,
    height: 700,
    events: eventsMensuel,
    contentHeight: 'auto',
    locale: 'fr',
    select: function(arg) {

      closeNav();
      var title = prompt('Titre du créneau:');
      if (title) { // si un titre d'événement a été saisi et que la limite d'événement autorisés n'a pas été dépassée
        mensuel.addEvent({title: title, start: arg.start, end: arg.end, allDay: true, classNames: ['plusBord']})
      }

      mensuel.unselect();
      mensuel.getEvents().forEach(event => {
        event.setProp("borderColor", "white");
      });
    },
    eventRender: function(info) {
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

      var tooltip = new tippy(info.el, {
        allowHTML: true,
        content: contenu,
        trigger: 'mouseenter',
        sticky: true,
        animation: 'shift-toward',
        maxWidth: 200
      });

    },
    eventClick: function(info) {
      mensuel.getEvents().forEach(event => {
        event.setProp("borderColor", "white");
      });
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

  });






  document.getElementById('submit2').onclick = function() {

console.log(saisieEnseignant);
if(saisieEnseignant){
var deltaRemarquePonctu = [];
var deltaCreneauxPonctu = [];
var compteurEventsMensuel = 0;
    // récup toutes les infos de l’enseignant saisies dans le formulaire

    creneauxSaisie = mensuel.getEvents();   // Les créneaux
    remarquePonctuSaisie = document.getElementById('form_remarquesPonctu').value; // Remarque ponctu

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


    creneauxSaisie.forEach(creneauxCourant => {
      if(events[compteurEventsMensuel] != null){
      if(creneauxCourant.title != eventsMensuel[compteurEventsMensuel].title ){
        deltaCreneauxPonctu.push("Modification du titre d'un ou plusieurs créneaux professionnels ponctuels");
      }
      if(moment(creneauxCourant.start).format('YYYY MM DD') != moment(eventsMensuel[compteurEventsMensuel].start).format('YYYY MM DD')
      || moment(creneauxCourant.end).format('YYYY MM DD') != moment(eventsMensuel[compteurEventsMensuel].end).format('YYYY MM DD') ){
        deltaCreneauxPonctu.push("Modification de la date d'un ou plusieurs créneaux professionnels ponctuels");
      }
      compteurEventsMensuel +=1;
    }});

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
        supprimerDesRemarques(enseignant);
        supprimerDesCreneaux("zoneGrisee");
        supprimerDesCreneaux("Evenement","");
        enregistrerDesRemarques(document.getElementById('form_remarquesHebdo').value,"Hebdomadaire",document.getElementById('form_remarquesPonctu').value,"Ponctuelle",enseignant);
      }

    creneaux = mensuel.getEvents(); //on récupère tous les événements du calendrier sous forme d'un tableau
    creneaux.forEach(creneau => enregistrerUnCreneau(creneau.start.toISOString(), creneau.end.toISOString(), creneau.title, "ContrainteProPonctu", "SansPrio", enseignant));
  };

  mensuel.render();

});
