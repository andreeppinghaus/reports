import xlwt, csv, os

for fol in os.listdir("data/"):
    if fol == "livro_vermelho_2013_revisao_2015":
        continue
    csv_folder = "data/"+fol+"/"
    if os.path.isdir(csv_folder):
        print fol

        book = xlwt.Workbook(encoding="UTF-8")
        for fil in os.listdir(csv_folder):
            if fil.endswith(".csv") and fil != "occurrences.csv" and fil != "occurrences_misses.csv":
                print fil
                sheet = book.add_sheet(fil[:-4])
                with open(csv_folder + fil) as filname:
                    reader = csv.reader(filname)
                    i = 0
                    for row in reader:
                        for j, each in enumerate(row):
                            sheet.write(i, j, each)
                        i += 1

        book.save("data/"+fol+"/all.xls")
