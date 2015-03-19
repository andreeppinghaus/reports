import xlwt, csv, os

for fol in os.listdir("./data/"):
    csv_folder = "./data/"+fol+"/"
    print fol

    book = xlwt.Workbook(encoding="UTF-8")
    for fil in os.listdir(csv_folder):
        if fil.endswith(".csv") and fil != "occurrences.csv":
            print fil
            sheet = book.add_sheet(fil[:-4])
            with open(csv_folder + fil) as filname:
                reader = csv.reader(filname)
                i = 0
                for row in reader:
                    for j, each in enumerate(row):
                        sheet.write(i, j, each)
                    i += 1

    book.save("./data/"+fol+"/all.xls")
