


document.addEventListener('DOMContentLoaded', function() {


const creneauObjet = {
  start: "",
  end: "",
  title: "",
  type: "",
  prio: "",
  enseignant: "",
}

  setTimeout(function(){
      var hebdoEl = document.getElementById('hebdo');

  var hebdo = new FullCalendar.Calendar(hebdoEl, {

    plugins: [
      'timeGrid', 'interaction', 'bootstrap'
    ],
    now: "2013-12-01T00:00:00",
    defaultView: 'timeGridWeek',
    defaultTimedEventDuration: '01:00',
    forceEventDuration: true,
    themeSystem: 'bootstrap',
    contentHeight: "auto",
    allDaySlot: false,
    slotDuration: echelle,
    slotLabelInterval: echelle,
    minTime: heureDebut,
    maxTime: heureFin,
    weekNumberCalculation: "ISO",
    weekends: false,
    selectable: true,
    events: events,
    columnHeaderFormat: {
      weekday: 'long'
    },
    editable: modifications,
    locale: 'fr',
    header: {
      left: '',
      center: '',
      right: ''
    },


    select: function(arg) {
      closeNav();
      if(estFormulaireTitulaire){
        hebdo.setOption('defaultTimedEventDuration',tempsParDefaut());
      }

      if(saisieEnseignant){
      if(detType()=="ContraintePro" || saisieEnseignant==false || titrevide==false){
        var title = prompt('Titre de la contrainte:');

      }
      else{var title=" ";}
      //Créneau disponibilité
      if(estFormulaireTitulaire==false){
        hebdo.addEvent({
          title: title,
          start: arg.start,
          end : arg.end,
          classNames: ['plusBord'],
          type: "Disponibilite",
          prio: detPrio(),
          color: detFond(),
          textColor: detTexteCouleur(),
        });
      }

    else if(saisieEnseignant && limiteDepassee()==false){
      if(!title){
        title=" ";
      }

        //Créneau contrainte perso
        if(detType()=="ContraintePerso"){
          hebdo.addEvent({
            title: title,
            start: arg.start,
            classNames: ['plusBord'],
            type: detType(),
            prio: detPrio(),
            color: detFond(),
            textColor: detTexteCouleur(),
          });
        }

        //Créneau contrainte pro
        else{

          hebdo.addEvent({
            title: title,
            start: arg.start,
            classNames: ['plusBord'],
            type: detType(),
            prio: detPrio(),
            color: detFond(),
            textColor: detTexteCouleur(),
          });
        }

        //Incrémentation des compteurs lorsque la limite n'a pas été dépassée dans une vue de saisie pour les enseignants
        if( document.getElementById('proForte').checked) {
          compteur.ContraintePro.proForte+=1;
          document.getElementById("quantiteProForte").innerHTML = (limiteProForte - compteur.ContraintePro.proForte).toString() + "/" + limiteProForte  ;
        }
        else if(document.getElementById('proMoy').checked) {
          compteur.ContraintePro.proMoy+=1;
          document.getElementById("quantiteProMoy").innerHTML = (limiteProMoy - compteur.ContraintePro.proMoy).toString() + "/" + limiteProMoy  ;

        }
        else if(document.getElementById('proFaible').checked) {
          compteur.ContraintePro.proFaible+=1;
          document.getElementById("quantiteProFaible").innerHTML = (limiteProFaible - compteur.ContraintePro.proFaible).toString() + "/" + limiteProFaible  ;

        }
        else if(document.getElementById('persForte').checked) {
          compteur.ContraintePerso.persoForte+=1;
          document.getElementById("quantitePersForte").innerHTML = (limitePersForte - compteur.ContraintePerso.persoForte).toString() + "/" + limitePersForte  ;

        }
        else if(document.getElementById('persMoy').checked) {
          compteur.ContraintePerso.persoMoy+=1;
          document.getElementById("quantitePersMoy").innerHTML = (limitePersMoy - compteur.ContraintePerso.persoMoy).toString() + "/" + limitePersMoy  ;

        }
        else if(document.getElementById('persFaible').checked) {
          compteur.ContraintePerso.persoFaible+=1;
          document.getElementById("quantitePersFaible").innerHTML = (limitePersFaible - compteur.ContraintePerso.persoFaible).toString() + "/" + limitePersFaible  ;

        }
      }
else if(limiteDepassee()==true){alert("Trop de contraintes de ce type saisies");}

    hebdo.unselect();
 }
    //Créneau zone grisée
    else {
      var title = prompt('Titre de la contrainte:');
      hebdo.addEvent({
        title: title,
        start: arg.start,
        end: arg.end,
        classNames: ['plusBord'],
      });
    }

  hebdo.getEvents().forEach(event => {
    event.setProp("borderColor", "white");
  });
},

eventRender: function(info) {
 if (info.event.extendedProps.type == "ContraintePro" && info.event.extendedProps.prio == "Forte") {
    info.el.querySelector('.fc-title').append(" [PRO] [FORTE] ");
  }
 else if (info.event.extendedProps.type == "ContraintePro" && info.event.extendedProps.prio == "Moyenne") {
     info.el.querySelector('.fc-title').append(" [PRO] [MOY] ");
   }
  else if (info.event.extendedProps.type == "ContraintePro" && info.event.extendedProps.prio == "Faible") {
      info.el.querySelector('.fc-title').append(" [PRO] [FAIBLE] ");
    }
 else if (info.event.extendedProps.type == "ContraintePerso" && info.event.extendedProps.prio == "Forte") {
    info.el.querySelector('.fc-title').append(" [PERSO] [FORTE] ");
  }
  else if (info.event.extendedProps.type == "ContraintePerso" && info.event.extendedProps.prio == "Moyenne") {
     info.el.querySelector('.fc-title').append(" [PERSO] [MOY] ");
   }
   else if (info.event.extendedProps.type == "ContraintePerso" && info.event.extendedProps.prio == "Faible") {
      info.el.querySelector('.fc-title').append(" [PERSO] [FAIBLE] ");
    }
    else if (info.event.extendedProps.type == "Disponibilite" && info.event.extendedProps.prio == "Forte") {
       info.el.querySelector('.fc-title').append(" [FORTE] ");
     }
    else if (info.event.extendedProps.type == "Disponibilite" && info.event.extendedProps.prio == "Moyenne") {
        info.el.querySelector('.fc-title').append(" [MOY] ");
      }
     else if (info.event.extendedProps.type == "Disponibilite" && info.event.extendedProps.prio == "Faible") {
         info.el.querySelector('.fc-title').append(" [FAIBLE] ");
       }


},
eventClick: function(info) {
  hebdo.getEvents().forEach(event => {
    event.setProp("borderColor", "white");
  });

  var dateDeb = hebdo.formatDate(info.event.start, {
    weekday: 'long',
    hour: '2-digit',
    minute: '2-digit',
    locale: 'fr'
  });
  var dateFin = hebdo.formatDate(info.event.end, {
    weekday: 'long',
    hour: '2-digit',
    minute: '2-digit',
    locale: 'fr'
  });

  document.getElementById('dateDebut').innerHTML = "Début : " + dateDeb;
  document.getElementById('dateFin').innerHTML =  "Fin : " + dateFin ;

var texte = "";
switch (info.event.extendedProps.type) {
  case "ContraintePro":
    texte = "Contrainte professionnelle";
    break;
    case "ContraintePerso":
    texte = "Contrainte personnelle";
      break;
      case "Disponibilite":
    texte = "Disponibilité";
        break;
  default:

}
  document.getElementById('type').innerHTML =  texte ;
  document.getElementById('prio').innerHTML =  "Priorité : " + info.event.extendedProps.prio ;
  if(info.event.extendedProps.type != "Disponibilite"){
    document.getElementById('nomcreneau').style.display="block";
    document.getElementById('titrevt').style.display="block";
    document.getElementById('type').style.display="block";
    document.getElementById('prio').style.display="block";
    document.getElementById('dateDebut').style.display="block";
    document.getElementById('dateFin').style.display="block";
    document.getElementById('apply').style.display="block";
    document.getElementById('remove').style.display="block";
    document.getElementById('titrevt').value = info.event.title;
  }
  if(info.event.extendedProps.type == "ContraintePerso" || info.event.extendedProps.type == "Disponibilite"){
    document.getElementById('nomcreneau').style.display="none";
    document.getElementById('titrevt').style.display="none";
    document.getElementById('apply').style.display="none";
    document.getElementById('remove').style.display="block";
    document.getElementById('type').style.display="block";
    document.getElementById('prio').style.display="block";
    document.getElementById('dateDebut').style.display="block";
    document.getElementById('dateFin').style.display="block";
  }

  info.event.setProp("borderColor", "red");


  document.getElementById('apply').onclick = function() {
    if (document.getElementById('titrevt').value != '') {
      info.event.setProp("title", document.getElementById('titrevt').value);
      hebdo.rerenderEvents();
    }
  openNav();

  };


  document.getElementById('remove').onclick = function() {
    if (confirm("Voulez vous vraiment supprimer ce créneau ?")) {
      info.event.remove();
      switch(info.event.extendedProps.type){
        case "ContraintePro":
        if(info.event.extendedProps.prio == "Forte"){
          compteur.ContraintePro.proForte-=1;
          document.getElementById("quantiteProForte").innerHTML = (limiteProForte - compteur.ContraintePro.proForte).toString() + "/" + limiteProForte  ;
        }
        else if(info.event.extendedProps.prio == "Moyenne"){
          compteur.ContraintePro.proMoy-=1;
          document.getElementById("quantiteProMoy").innerHTML = (limiteProMoy - compteur.ContraintePro.proMoy).toString() + "/" + limiteProMoy  ;
        }
        else if(info.event.extendedProps.prio == "Faible"){
          compteur.ContraintePro.proFaible-=1;
          document.getElementById("quantiteProFaible").innerHTML = (limiteProFaible - compteur.ContraintePro.proFaible).toString() + "/" + limiteProFaible  ;
        }
        break;

        case "ContraintePerso":
        if(info.event.extendedProps.prio == "Forte"){
          compteur.ContraintePerso.persoForte-=1;
          document.getElementById("quantitePersForte").innerHTML = (limitePersForte - compteur.ContraintePerso.persoForte).toString() + "/" + limitePersForte  ;
        }
        else if(info.event.extendedProps.prio == "Moyenne"){
          compteur.ContraintePerso.persoMoy-=1;
          document.getElementById("quantitePersMoy").innerHTML = (limitePersMoy - compteur.ContraintePerso.persoMoy).toString() + "/" + limitePersMoy  ;
        }
        else if(info.event.extendedProps.prio == "Faible"){
          compteur.ContraintePerso.persoFaible-=1;
          document.getElementById("quantitePersFaible").innerHTML = (limitePersFaible - compteur.ContraintePerso.persoFaible).toString() + "/" + limitePersFaible  ;
        }
        break;
      }

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



document.getElementById('submit').onclick = function() {


if(saisieEnseignant){



  //LOG HEBDO
  var deltaRemarqueHebdo = [];
  var deltaCreneauxHebdo = [];
  var compteurEventsHebdo = 0;
      // récup toutes les infos de l’enseignant saisies dans le formulaire

      creneauxHebdoSaisie = hebdo.getEvents();   // Les créneaux
      remarqueHebdoSaisie = document.getElementById('remarquesHebdo').value; // Remarque HEBDO

      // récup toutes les infos de l’enseignant en BD

      // Events hebdo -> creneauxEnseignantSansGrisee
      // Remarque hebdo -> remarqueHebdo


      // Calculer le delta pour enregistrer dans le log

      // Delta sur les remarques
      if(!(remarqueHebdoSaisie == remarqueHebdo)){

        if(remarqueHebdoSaisie == "" && remarqueHebdo != ""){
          deltaRemarqueHebdo.push("Suppression de la remarque sur les contraintes hebdomadaires");
        }
        else if (remarqueHebdoSaisie != "" && remarqueHebdo == ""){
          deltaRemarqueHebdo.push("Ajout de la remarque sur les contraintes hebdomadaires");
        }
        else {
          deltaRemarqueHebdo.push("Modification de la remarque sur les contraintes hebdomadaires");
        }
      }


      //Delta sur les créneaux

      if(creneauxEnseignantSansGrisee.length > creneauxHebdoSaisie.length){
        deltaCreneauxHebdo.push("Suppression de créneaux hebdomadaires");
      }
      if(creneauxEnseignantSansGrisee.length < creneauxHebdoSaisie.length){
        var texte = "Ajout de créneaux hebdomadaires";
        var indice = (creneauxHebdoSaisie.length - (creneauxHebdoSaisie.length - creneauxEnseignantSansGrisee.length)) + 1;
        console.log(indice);
        for (var i = indice; i < creneauxHebdoSaisie.length-1; i++) {
          texte += " (Nouveau créneau : " + creneauxEnseignantSansGrisee[i].title + ")";
        }
        deltaCreneauxHebdo.push(texte);
      }



      creneauxHebdoSaisie.forEach(creneauxCourant => {
        if(creneauxEnseignantSansGrisee[compteurEventsHebdo] != null){
        if(creneauxCourant.title != creneauxEnseignantSansGrisee[compteurEventsHebdo].title ){
          deltaCreneauxHebdo.push("Modification du titre d'un ou plusieurs créneaux hebdomadaires (Ancien titre : " + creneauxEnseignantSansGrisee[compteurEventsHebdo].title
          + " - Nouveau titre : " + creneauxCourant.title + ")");
        }

        compteurEventsHebdo +=1;
      }});

      //Envoie des logs à LogEnseignantController
      if(deltaRemarqueHebdo.length == 0 && deltaCreneauxHebdo.length == 0){
        envoyerLogHebdo("Aucune modif remarque", "Aucune modif créneau", enseignant);
      }
      else if(deltaRemarqueHebdo.length == 0){
        envoyerLogHebdo("Aucune modif remarque", deltaCreneauxHebdo, enseignant);
      }
      else if (deltaCreneauxHebdo.length == 0){
        envoyerLogHebdo(deltaRemarqueHebdo, "Aucune modif créneau", enseignant);
      }
      else{
        envoyerLogHebdo(deltaRemarqueHebdo, deltaCreneauxHebdo, enseignant);
      }







      //LOG Ponctuelle


}



















//CALENDRIER HEBDO DANS FORMULAIRE VACATAIRE
  if(saisieEnseignant && estFormulaireTitulaire == false){
    supprimerDesCreneaux("Disponibilite", enseignant);
  }

//CALENDRIER HEBDO DANS FORMULAIRE TITULAIRE
  if (saisieEnseignant && estFormulaireTitulaire) {
    supprimerDesCreneaux("ContraintePro", enseignant);
    supprimerDesCreneaux("ContraintePerso", enseignant);
  }

//CALENDRIER HEBDO PAGE ADMIN
  if(saisieEnseignant == false){
    supprimerDesCreneaux("zoneGrisee");
    var tableauCreneaux = [];
    creneaux = hebdo.getEvents();
    creneaux.forEach(function(creneau){
      var aAjouterAuTableau = Object.create(creneauObjet);
      aAjouterAuTableau.start = creneau.start.toISOString();
      aAjouterAuTableau.end = creneau.end.toISOString();
      aAjouterAuTableau.title = creneau.title;
      aAjouterAuTableau.type = "zoneGrisee";
      aAjouterAuTableau.prio = "sansPrio";
      tableauCreneaux.push(aAjouterAuTableau);
    });
   enregistrerDesCreneaux(tableauCreneaux);
  }

//CALENDRIER HEBDO DANS FORMULAIRE QUELCONQUE
else{
  supprimerDesRemarques(enseignant);
  enregistrerDesRemarques(document.getElementById('remarquesHebdo').value,"Hebdomadaire",document.getElementById('remarquesPonctu').value,"Ponctuelle",enseignant);

  var tableauCreneaux = [];
  creneaux = hebdo.getEvents();
  creneaux.forEach(function(creneau){
    if(creneau.extendedProps.type != 'zoneGrisee'){
    var aAjouterAuTableau = Object.create(creneauObjet);
    aAjouterAuTableau.start = creneau.start.toISOString();
    aAjouterAuTableau.end = creneau.end.toISOString();
    aAjouterAuTableau.title = creneau.title;
    aAjouterAuTableau.type = creneau.extendedProps.type;
    aAjouterAuTableau.prio = creneau.extendedProps.prio;
    aAjouterAuTableau.enseignant = enseignant;
    tableauCreneaux.push(aAjouterAuTableau);
  }
  });
 enregistrerDesCreneaux(tableauCreneaux);
  document.getElementById('submit2').click();
  document.getElementById('submit3').click();
 changerRegroupementEnseignements(document.getElementById('regroupement').value,enseignant);
}



};

hebdo.render();


if(saisieEnseignant){

  //On compte tous les créneaux déjà présents sur le calendrier et on incrémente les compteurs en fonction
  creneaux = hebdo.getEvents();
  creneaux.forEach(creneau => {
    switch(creneau.extendedProps.type){
      case "ContraintePro":
      if(creneau.extendedProps.prio == "Forte"){
        compteur.ContraintePro.proForte+=1;
        document.getElementById("quantiteProForte").innerHTML = (limiteProForte - compteur.ContraintePro.proForte).toString() + "/" + limiteProForte  ;

      }
      else if(creneau.extendedProps.prio == "Moyenne"){
        compteur.ContraintePro.proMoy+=1;
        document.getElementById("quantiteProMoy").innerHTML = (limiteProMoy - compteur.ContraintePro.proMoy).toString() + "/" + limiteProMoy  ;

      }
      else if(creneau.extendedProps.prio == "Faible"){
        compteur.ContraintePro.proFaible+=1;
        document.getElementById("quantiteProFaible").innerHTML = (limiteProFaible - compteur.ContraintePro.proFaible).toString() + "/" + limiteProFaible  ;

      }
      break;

      case "ContraintePerso":
      if(creneau.extendedProps.prio == "Forte"){
        compteur.ContraintePerso.persoForte+=1;
        document.getElementById("quantitePersForte").innerHTML = (limitePersForte - compteur.ContraintePerso.persoForte).toString() + "/" + limitePersForte  ;

      }
      else if(creneau.extendedProps.prio == "Moyenne"){
        compteur.ContraintePerso.persoMoy+=1;
        document.getElementById("quantitePersMoy").innerHTML = (limitePersMoy - compteur.ContraintePerso.persoMoy).toString() + "/" + limitePersMoy  ;

      }
      else if(creneau.extendedProps.prio == "Faible"){
        compteur.ContraintePerso.persoFaible+=1;
        document.getElementById("quantitePersFaible").innerHTML = (limitePersFaible - compteur.ContraintePerso.persoFaible).toString() + "/" + limitePersFaible  ;

      }
      break;
    }

  });
}}, 50);


});
