#!/usr/bin/python
from bs4 import BeautifulSoup

devPath = "../coverage_frontend/Chrome Headless 112.0.5614.0 (Mac OS 10.15.7)"
pipelinePath = "../coverage_frontend/Chrome Headless 112.0.5614.0 (Linux x86_64)"
paths = [
    "/services/index.html",
    "/controllers/index.html",
    "/controllers/private/index.html"
]

avoidedFiles = ["messageService.js", "requestService.js", "home.js"]

for path in paths:
    with open(pipelinePath+path, 'r') as file:
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
                elif (index == 2):

                    if (className in avoidedFiles):
                        break

                    value = float(item.text.split("%")[0])
                    if (value < 70):
                        raise Exception("\nThe file "+className+" has "+str(value)+"% of code coverage, but it is expected 70% at least\n")

                index = index + 1