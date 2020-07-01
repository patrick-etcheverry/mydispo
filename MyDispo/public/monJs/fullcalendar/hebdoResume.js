


document.addEventListener('DOMContentLoaded', function() {



  setTimeout(function(){var hebdoEl = document.getElementById('hebdo');
  var hebdo = new FullCalendar.Calendar(hebdoEl, {

    plugins: [
      'timeGrid', 'bootstrap'
    ],
    now: "2013-12-01T00:00:00",
    defaultView: 'timeGridWeek',
    themeSystem: 'bootstrap',
    contentHeight: "auto",
    allDaySlot: false,
    slotDuration: echelle,
    slotLabelInterval: echelle,
    minTime: heureDebut,
    maxTime: heureFin,
    weekNumberCalculation: "ISO",
    weekends: false,
    events: events,
    columnHeaderFormat: {
      weekday: 'long'
    },
    locale: 'fr',
    header: {
      left: '',
      center: '',
      right: ''
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
    }

  });

  hebdo.render();

},50);});
