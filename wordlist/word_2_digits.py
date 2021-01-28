rules = (
        (0, ("ZZ", "Z","SCI", "SCE", "SS", "S")),
        (5, ("GLI", "LL", "L")),
        (2, ("GN", "NN", "N")),
        (6, ("CCI", "CI", "CCE", "CE", "GGI", "GI", "GGE", "GE")),
        (7, ("QQ", "CC", "CQ", "GG", "G", "C", "Q", "K")),
        (4, ("RR", "R")),
        (3, ("MM", "M")),
        (1, ("TT", "T", "DD", "D")),
        (8, ("FF", "F", "VV", "V")),
        (9, ("BB", "B", "PP", "P"))
    )

def word_2_digits(txt):

    txt = txt.upper()
    for (num, lista) in rules:
        for elem in lista:
            txt = txt.replace(elem, str(num))

    txt = filter(lambda x: x.isdigit(), txt)
    
    return txt

