
  String.prototype.minsToHHMMSS = function() {
    var mins_num = parseFloat(this, 10); // don't forget the second param
    var hours = Math.floor(mins_num / 60);
    var minutes = Math.floor((mins_num - ((hours * 3600)) / 60));
    var seconds = Math.floor((mins_num * 60) - (hours * 3600) - (minutes * 60));

    // Appends 0 when unit is less than 10
    if (hours < 10) {
      hours = "0" + hours;
    }
    if (minutes < 10) {
      minutes = "0" + minutes;
    }
    if (seconds < 10) {
      seconds = "0" + seconds;
    }
    return hours + ':' + minutes + ':' + seconds;
  }

  function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
    document.getElementById("main").style.marginRight = "250px";

  }

  /* Set the width of the side navigation to 0 and the left margin of the page content to 0 */
  function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
    document.getElementById("main").style.marginRight = "0";
  }

  var events;
  function ajax(start, end, title, type) {

    $.ajax({
      url: "{{path("creneau_ajouter")}}",
      data: {
        startevt: start,
        endevt: end,
        titleevt: title,
        typeevt: type
      }, //données envoyées au serveur pour chaque événement
      type: "POST"
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [
        'interaction', 'dayGrid'
      ],
      defaultView: 'dayGridMonth',
      selectable: true,
      editable: true,
      height: 700,
      events: {{ events|raw  }},
      contentHeight: 'auto',
        locale: 'fr',
        select: function(arg) {

          closeNav();
          var title = prompt('Titre du créneau:');
          if (title) { // si un titre d'événement a été saisi et que la limite d'événement autorisés n'a pas été dépassée
            calendar.addEvent({title: title, start: arg.start, end: arg.end, allDay: true, classNames: ['plusBord']})
          }

          calendar.unselect();
          calendar.getEvents().forEach(event => {
            event.setProp("borderColor", "white");
          });
        },
        eventRender: function(info) {
          var dateDeb = calendar.formatDate(info.event.start, {
            weekday: 'long',
            day: 'numeric',
            year: 'numeric',
            month: 'long',
            locale: 'fr'
          });
          var dateFin = calendar.formatDate(info.event.end, {
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
          calendar.getEvents().forEach(event => {
            event.setProp("borderColor", "white");
          });
          document.getElementById('nomcreneau').innerHTML = "Contrainte " + info.event.title;
          document.getElementById('titrevt').value = info.event.title;
          info.event.setProp("borderColor", "red");

          document.getElementById('apply').onclick = function() {
            if (document.getElementById('titrevt').value != '') {
              info.event.setProp("title", document.getElementById('titrevt').value);
              document.getElementById("nomcreneau").innerHTML = "Contrainte " + info.event.title;
              calendar.rerenderEvents();
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

      document.getElementById('submit').onclick = function() {
        events = calendar.getEvents(); //on récupère tous les événements du calendrier sous forme d'un tableau
        events.forEach(event => ajax(event.start.toISOString(), event.end.toISOString(), event.title, "evenement")); //pour chaque élément du tableau, c'est à dire pour chaque événement, on envoie sa date de début, sa date de fin et son titre au serveur
      };

    calendar.render();

  });
