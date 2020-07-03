

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
    timeZone: 'local',
    selectOverlap: false,
    select: function(arg) {

      closeNav();
      document.getElementById('apply').innerHTML = "<i class='far fa-save'></i> Créer";
      document.getElementById('remove').innerHTML = "<i class='fas fa-trash-alt'></i> Annuler";
      document.getElementById('titrevt').value = "";


                  //Paramétrage du menu à afficher
                  document.getElementById('nomcreneau').style.display="none";
                  document.getElementById('titrevt').style.display="block";
                  document.getElementById('type').style.display="none";
                  document.getElementById('prio').style.display="none";
                  document.getElementById('dateDebut').style.display="none";
                  document.getElementById('dateFin').style.display="none";
                  document.getElementById('texteExplicatif').style.display = "block";
                  document.getElementById('apply').style.display="block";
                  document.getElementById('remove').style.display="block";

                  openNav();

                    document.getElementById('apply').onclick = function(){
                      if(document.getElementById('titrevt').value != "" &&  /\w/.test(document.getElementById('titrevt').value)){
                        var title = document.getElementById('titrevt').value;
                          mensuel.addEvent({title: title, start: arg.start, end: arg.end, allDay: true, classNames: ['plusBord']});
                          closeNav();
                          mensuel.unselect();
                        }
                        else{
                          alert(" Erreur : Un titre valide doit être saisi");
                            document.getElementById('titrevt').value = "";
                        }};

                        document.getElementById('remove').onclick = function(){
                          closeNav();
                          mensuel.unselect();
                        };

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


    },
    eventClick: function(info) {

      document.getElementById('apply').innerHTML = "<i class='far fa-save'></i> Appliquer les modifications";
      document.getElementById('remove').innerHTML = "<i class='fas fa-trash-alt'></i> Supprimer le créneau";


      if(info.event.rendering != "background"){
      mensuel.getEvents().forEach(event => {
        event.setProp("borderColor", "white");
      });

      document.getElementById('texteExplicatif').style.display="block";


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

eventsMensuelSansGrisee = [];
creneauxSaisieSansGrisee = [];

eventsMensuel.forEach(creneauCourant => {
  if (creneauCourant.type != "Evenement"){
    eventsMensuelSansGrisee.push(creneauCourant);
  }
});

creneauxSaisie.forEach(creneauCourant => {
  if (creneauCourant.extendedProps.type != "Evenement"){
    creneauxSaisieSansGrisee.push(creneauCourant);
  }
});


//Delta sur les créneaux
if(eventsMensuelSansGrisee.length > creneauxSaisieSansGrisee.length){
deltaCreneauxPonctu.push("Suppression de créneaux professionnels ponctuels");
}
if(eventsMensuelSansGrisee.length < creneauxSaisieSansGrisee.length){
deltaCreneauxPonctu.push("Ajout de créneaux professionnels ponctuels");
}





creneauxSaisieSansGrisee.forEach(creneauxCourant => {
  if(eventsMensuelSansGrisee[compteurEventsMensuel] != null){
    if(creneauxCourant.title != eventsMensuelSansGrisee[compteurEventsMensuel].title ){
      deltaCreneauxPonctu.push("Modification du titre d'un ou plusieurs créneaux hebdomadaires (Ancien titre : " + eventsMensuelSansGrisee[compteurEventsMensuel].title
      + " - Nouveau titre : " + creneauxCourant.title + ")");
    }}
    compteurEventsMensuel +=1;
  });







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


  var tableauCreneaux = [];
  creneaux = mensuel.getEvents();
  creneaux.forEach(function(creneau){
    if(creneau.extendedProps.type != "Evenement"){
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
  supprimerEtEnregistrerDesCreneauxPonctuels(tableauCreneaux,enseignant);
}


if(saisieEnseignant == false){


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
    supprimerEtEnregistrerDesCreneauxEvenement(tableauCreneaux);
}
}

mensuel.render();

});
