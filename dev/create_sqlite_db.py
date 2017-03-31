import word_2_digits
import sqlite3

WORDLIST_FILENAME = "diz"

def words_gen():
    with open(WORDLIST_FILENAME) as wordlist:
        for word in wordlist:
            yield word.strip()


conn = sqlite3.connect('diz_ita.db')
c = conn.cursor()

c.execute("CREATE TABLE words (word text, digits text)")

for word in words_gen():
    digits = word_2_digits.word_2_digits(word)
    c.execute("INSERT INTO words VALUES ('{}', '{}')".format(word, digits))

conn.commit()

conn.close()
