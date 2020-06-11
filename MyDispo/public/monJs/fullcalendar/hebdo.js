document.addEventListener('DOMContentLoaded', function() {



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
          hebdo.setOption('defaultTimedEventDuration',tempsParDefaut());
          var title = prompt('Titre de la contrainte:');
          if(limiteDepassee()==false){
          if (title) {
            if (saisieEnseignant) { // si un titre d'événement a été saisi et que la limite d'événement autorisés n'a pas été dépassée
              hebdo.addEvent({
                title: title,
                start: arg.start,
                classNames: ['plusBord'],
                type: detType(),
                prio: detPrio(),
                borderColor: detBord(),
                color: detFond(),
                textColor: "black",
              });
              if( document.getElementById('proForte').checked) {
                compteur.ContraintePro.proForte+=1;
              }
              else if(document.getElementById('proMoy').checked) {
                compteur.ContraintePro.proMoy+=1;
              }
              else if(document.getElementById('proFaible').checked) {
                compteur.ContraintePro.proFaible+=1;
              }
              else if(document.getElementById('persForte').checked) {
                compteur.ContraintePerso.persoForte+=1;
              }
              else if(document.getElementById('persMoy').checked) {
                compteur.ContraintePerso.persoMoy+=1;
                }
              else if(document.getElementById('persFaible').checked) {
                compteur.ContraintePerso.persoFaible+=1;
              }
            }
            else {
              hebdo.addEvent({
                title: title,
                start: arg.start,
                end: arg.end,
                classNames: ['plusBord'],
              });

            }
          }
          hebdo.unselect();
        }
        else{alert("Trop de contraintes de ce type saisies");hebdo.unselect();}

       hebdo.getEvents().forEach(event => {
        event.setProp("borderColor", "white");
      });
    },

    eventRender: function(info) {
      if (info.event.extendedProps.type == "ContraintePro") {
        info.el.querySelector('.fc-title').append(" [PRO] ");
      } else if (info.event.extendedProps.type == "ContraintePerso") {
        info.el.querySelector('.fc-title').append(" [PERSO] ");
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
      document.getElementById('dateFin').innerHTML = "Fin : " + dateFin;
      document.getElementById('nomcreneau').innerHTML = "Contrainte " + info.event.title;
      document.getElementById('titrevt').value = info.event.title;
      info.event.setProp("borderColor", "red");

      document.getElementById('apply').onclick = function() {
        if (document.getElementById('titrevt').value != '') {
          info.event.setProp("title", document.getElementById('titrevt').value);
          document.getElementById("nomcreneau").innerHTML = "Contrainte " + info.event.title;
          hebdo.rerenderEvents();
        }

      };


      document.getElementById('remove').onclick = function() {
        if (confirm("Voulez vous vraiment supprimer ce créneau ?")) {
          info.event.remove();
          switch(info.event.extendedProps.type){
            case "ContraintePro":
                if(info.event.extendedProps.prio == "Forte"){
                  compteur.ContraintePro.proForte-=1;
                }
                else if(info.event.extendedProps.prio == "Moyenne"){
                  compteur.ContraintePro.proMoy-=1;
                }
                else if(info.event.extendedProps.prio == "Faible"){
                  compteur.ContraintePro.proFaible-=1;
                }
              break;

              case "ContraintePerso":
              if(info.event.extendedProps.prio == "Forte"){
                compteur.ContraintePerso.persoForte-=1;
              }
              else if(info.event.extendedProps.prio == "Moyenne"){
                compteur.ContraintePerso.persoMoy-=1;
              }
              else if(info.event.extendedProps.prio == "Faible"){
                compteur.ContraintePerso.persoFaible-=1;
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

  if (saisieEnseignant) {
    supprimerDesCreneaux("ContraintePro", enseignant);
    supprimerDesCreneaux("ContraintePerso", enseignant);
    supprimerDesRemarques(enseignant);
    enregistrerDesRemarques(document.getElementById('form_remarquesHebdo').innerHTML,"hebdo",document.getElementById('form_remarquesPonctu').innerHTML,"ponctu",enseignant);
  }
  else {
    supprimerDesCreneaux("zoneGrisee");
  }
  creneaux = hebdo.getEvents(); //on récupère tous les événements du calendrier sous forme d'un tableau
  creneaux.forEach(creneau => enregistrerUnCreneau(creneau.start.toISOString(), creneau.end.toISOString(), creneau.title, creneau.extendedProps.type, creneau.extendedProps.prio, enseignant));

};

hebdo.render();

if (saisieEnseignant) {

  //On compte tous les créneaux déjà présents sur le calendrier et on incrémente les compteurs en fonction
  creneaux = hebdo.getEvents();
  creneaux.forEach(creneau => {
    switch(creneau.extendedProps.type){
      case "ContraintePro":
          if(creneau.extendedProps.prio == "Forte"){
            compteur.ContraintePro.proForte+=1;
          }
          else if(creneau.extendedProps.prio == "Moyenne"){
            compteur.ContraintePro.proMoy+=1;
          }
          else if(creneau.extendedProps.prio == "Faible"){
            compteur.ContraintePro.proFaible+=1;
          }
        break;

        case "ContraintePerso":
        if(creneau.extendedProps.prio == "Forte"){
          compteur.ContraintePerso.persoForte+=1;
        }
        else if(creneau.extendedProps.prio == "Moyenne"){
          compteur.ContraintePerso.persoMoy+=1;
        }
        else if(creneau.extendedProps.prio == "Faible"){
          compteur.ContraintePerso.persoFaible+=1;
        }
          break;
    }

  });
}



});