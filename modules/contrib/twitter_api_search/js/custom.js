/* global twttr */
(function ($) {
  'use strict';

  Drupal.behaviors.twitterApiSearch = {
    attach(context, settings) {
      const twitterApiSearch = {

        removeFooters() {
          if (typeof twttr !== 'undefined') {
            twttr.ready((twttr) => {
              twttr.events.bind('rendered', (event) => {
                $(event.target.shadowRoot).find('.CallToAction').hide();
              });
            });
          }
        },

        ini() {
          twitterApiSearch.removeFooters();
        }

      };
      twitterApiSearch.ini();
    }
  };
}(jQuery));
