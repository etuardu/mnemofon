/* mnemofon.js */

var n = ''; // value of the txt_number field
var chunks = []; // the number, splitted by spaces
var ajaxTimers = []; // for delayied calls

$('#txt_number').on('input', function() {

  // return if the text is unchanged
  var t = $('#txt_number').val();
  if (t == n) return;
  n = t;

  // skim out empty items (due to multiple/trailing spaces)
  var t_chunks = n.split(' ').filter(function(x) {return x != ''});

  // amount of tds in the table
  var td_count = $('#tbl_words td').length;

  if (td_count > t_chunks.length) {
    // remove exceeding tds
    $('#tbl_words td:gt('+(t_chunks.length-1)+')').remove();
  }
  if (td_count < t_chunks.length) {
    // add needed tds
    for (var i=td_count; i<t_chunks.length; i++) {
      $('#tbl_words tr').append('<td><div class="dv_wordlist"></div></td>');
    }
  }

  // divs where to put the word lists
  var divs = $('#tbl_words div.dv_wordlist');

  for (var i=0; i<t_chunks.length; i++) {
    if (t_chunks[i] == chunks[i]) continue;

    $(divs[i]).text('...');

    if (ajaxTimers.length <= i) {
      // make room to put the timer
      ajaxTimers.push(null);
    } else {
      // clear previously set timer
      clearTimeout(ajaxTimers[i]);
    }

    // delayied ajax call to populate the div.
    // The div (context) and the number (n) are
    // passed in the timeout by a closure.
    ajaxTimers[i] = setTimeout(function(context, n) {
      return function() {
        $.ajax({
          url: "mnemofon.php",
          type: "get",
          data: {
            n: n
          },
          context: context,
          dataType: 'json'
        }).done(function(data) {
          $(this).html(data.words.join('<br>'));
        });
      };
    }($(divs[i]), t_chunks[i]), 700);
  }

  chunks = t_chunks;

});
