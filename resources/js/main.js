$( document ).ready(function() {
    $('.add-guest').on('click', (e) => {
        e.preventDefault();
        e.stopPropagation();

        let playerID = $(e.target).data('player');
		let guestField = $('.guest-field[data-player=' + playerID + ']');
		let playerField = $('.friend-selector[data-player=' + playerID + ']');

        guestField.show();
		guestField.attr('required', 'required');
		playerField.removeAttr('required');
		playerField.val('');
    });

    $('.friend-selector').change((e) => {
        let playerID = $(e.target).data('player');
        let guestField = $('.guest-field[data-player=' + playerID + ']');
        let playerField = $('.friend-selector[data-player=' + playerID + ']');

		playerField.attr('required', 'required');
		guestField.hide();
		guestField.removeAttr('required');

		if ( $('.friend-selector[data-player=1]').val() > 0 && $('.friend-selector[data-player=2]').val() > 0 && $('.friend-selector[data-player=3]').val() > 0 && $('.friend-selector[data-player=4]').val() > 0 ) {
            $('.randomize-teams').show();
        } else {
            $('.randomize-teams').hide();
        }
    });

    $('.randomize-teams').click((e) => {
        e.preventDefault();
        e.stopPropagation();

        let oldValues = {
            1: {
                player: $('.friend-selector[data-player=1]').val(),
                guest: $('.guest-field[data-player=1]').val(),
            },
            2: {
                player: $('.friend-selector[data-player=2]').val(),
                guest: $('.guest-field[data-player=2]').val(),
            },
            3: {
                player: $('.friend-selector[data-player=3]').val(),
                guest: $('.guest-field[data-player=3]').val(),
            },
            4: {
                player: $('.friend-selector[data-player=4]').val(),
                guest: $('.guest-field[data-player=4]').val(),
            }
        };

        let order = shuffle( [ 1, 2, 3, 4] );
        order.map((newPosition, row) => {
            $('.friend-selector[data-player=' + newPosition + ']').val(oldValues[row+1].player);
            $('.guest-field[data-player=' + newPosition + ']').val(oldValues[row+1].guest);
        });
    });

    function shuffle(a) {
        var j, x, i;
        for (i = a.length - 1; i > 0; i--) {
            j = Math.floor(Math.random() * (i + 1));
            x = a[i];
            a[i] = a[j];
            a[j] = x;
        }
        return a;
    }

    $("input[name='start_game_options']").change(function(){
        let inputValue = $(this).val();
        let gameScoresDiv = $('.create-game-score');

        if (inputValue == 'add_score') {
            gameScoresDiv.show();
        } else {
            gameScoresDiv.hide();
        }
    });
});
