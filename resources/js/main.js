$( document ).ready(function() {
    $('.add-guest').on('click', (e) => {
        e.preventDefault();
        e.stopPropagation();

        let playerID = $(e.target).data('player');
        $('.guest-field[data-player=' + playerID + ']').show();

    });

    $('.friend-selector').change((e) => {
        let playerID = $(e.target).data('player');
        $('.guest-field[data-player=' + playerID + ']').hide();
    });
});
