"use strict";

Vue.ready(function () {
  UIkit.util.findAll('time').forEach(function (time) {
    new Vue({}).$mount(time);
  });
});