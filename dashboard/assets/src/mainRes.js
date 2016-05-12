require.config({
  paths: {
    "moment": "../lib/moment.min",
    "knockout": "../lib/knockout-2.2.1",
  }
});

require(["timecalcRes"], function(timecalc) {
  // it initializes itself now.
});
