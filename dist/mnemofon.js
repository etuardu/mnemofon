let n = ''; // value of the txt_number field
let chunks = []; // the number, splitted by spaces
let ajaxTimers = []; // for delayed calls

function onInput(t) {

  if (t == n) return;
  // return if the text is unchanged

  n = t;

  // split on spaces and skip empty lines
  const t_chunks = n.split(' ').filter(x => x != '');

  let tds = document.querySelectorAll("#words_table div");

  // remove exceeding tds
  tds.forEach( (e, i) => {
    if ((i == 0) || (i < t_chunks.length)) return;
    e.parentNode.removeChild(e);
  });

  if (t_chunks.length == 0) {
    tds[0].innerText = '';
    return;
  }

  t_chunks.forEach( (e, i) => {
    // add td if needed
    if (i >= tds.length) {
      document.getElementById("words_table").appendChild(
        document.createElement("div")
      );
      tds = document.querySelectorAll("#words_table div");
      // update array
    }

    if (t_chunks[i] == chunks[i]) return;

    tds[i].innerText = "...";

    if (ajaxTimers.length <= i) {
      // make room in the array to put the timer
      ajaxTimers.push(null);
    } else {
      // clear a previously set timer
      clearTimeout(ajaxTimers[i]);
    }

    ajaxTimers[i] = setTimeout(async () => {
      try {
        const req = await fetch(
          `mnemofon.php?n=${t_chunks[i]}`
        );
        const data = await req.json();
        console.log(data);
        if (data.err != 0) {
          // invalid input
          tds[i].innerHTML = '<i>Caratteri non validi</i>';
        } else if (data.words.length > 0) {
          // found
          tds[i].innerHTML = data.words.join('<br>');
        } else {
          // nothing found
          tds[i].innerHTML = '<i>Nessun risultato</i>';
        }
      } catch (err){
        tds[i].innerHTML = '<i>Errore server</i>';
      }
    }, 800);

  });

  chunks = t_chunks;

}

function toggleInfoDialog(s) {
  document.getElementById(
    "info_dialog"
  ).style.display = s ? "flex" : "none";
};

window.addEventListener("load", () => {
  document.getElementById(
    "btn_close"
  ).addEventListener("click", () => {
    toggleInfoDialog(false);
  });
  document.getElementById(
    "btn_about"
  ).addEventListener("click", () => {
    toggleInfoDialog(true);
  });
  document.getElementById(
    "txt_number"
  ).addEventListener("input", e => {
    onInput(e.target.value);
  });
});
