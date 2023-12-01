#!/usr/bin/python
from bs4 import BeautifulSoup

with open('../coverage_reports/dashboard.html', 'r') as file:
    contents = file.read()

    soup = BeautifulSoup(contents, 'lxml')

    coverageTable = soup.select("table")[0]
    rows = coverageTable.select("tr")

    for row in rows:
        tableData = row.select("td")
        
        index = 0
        className = ""
        for item in tableData:
            if (index == 0):
                className = item.text
            else:
                value = int(item.text.split("%")[0])

                if (not className.startswith("App\\Http\\Middleware\\") 
                    and not className.startswith("App\\Providers")
                    and not className.startswith("App\\Console")
                    and not className.startswith("App\\Mail")
                    and not className.endswith("BotManController")):
                    
                    if (value < 90):
                        raise Exception("\nThe file "+className+" has "+str(value)+"% of code coverage, but it is expected 90% at least\n")
            index = index + 1