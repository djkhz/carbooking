function initCarCalendar() {
  new Calendar("car-calendar", {
    url: WEB_URL + "index.php/car/model/calendar/toJSON",
    onclick: function(d) {
      send(
        WEB_URL + "index.php/car/model/index/action",
        "action=detail&id=" + this.id,
        doFormSubmit
      );
    }
  });
}