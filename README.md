# ArgFrame
## Introduction
This argumentation framework has been proposed as a tool to perform automated reasoning with numerical data. It is able to use boolean logic for the creation of if-then rules and attacking rules. In turn, these rules can be activated by data, have their attacks solved, and finally aggregated in different fashions in order to produce a prediction (a number). This process works in the following order:

1. feature set creation;

![Screenshot from 2022-06-28 11-45-04](https://user-images.githubusercontent.com/55241866/176161779-e752748c-ae3b-4ea9-a12c-d44b6323e60c.png)

2. creation of rules and attacks employing the features created in (1), which results in an argumentation graph;

![Screenshot from 2022-06-28 11-56-36](https://user-images.githubusercontent.com/55241866/176162633-9ee19e22-0d9e-44f6-a36c-b38c95d0b58f.png)

![Screenshot from 2022-06-28 11-58-37](https://user-images.githubusercontent.com/55241866/176162992-e4e337ef-8177-45d2-91ad-00040afa7f13.png)

3. instantiation of graph(s) from (2) with numerical data and case-by-case analysis with computation of predictions;

![Screenshot from 2022-06-28 12-01-44](https://user-images.githubusercontent.com/55241866/176163525-4749dff7-1b93-48fb-8f41-13acb855a3a0.png)

4. exportation of all results to csv file for any number of combinations of agumentation semantics and accrual strategies.

![Screenshot from 2022-06-28 12-04-07](https://user-images.githubusercontent.com/55241866/176163881-c634b204-27d7-4b5b-a3ba-f40c60f2225c.png)

A running example can be seen here https://lucasrizzo.com/framework/index.php

## Getting started

1. Clone the repo by running this command in the terminal:

`git clone https://github.com/SoftwareImpacts/SIMPAC-2023-305.git`

2. Make sure to run the command git pull (if you already cloned this repo):

`git pull git clone https://github.com/SoftwareImpacts/SIMPAC-2023-305.git`

3. Build docker image by running below commands in the terminal (make sure docker is running and you have docker-compose installed):

```
cd SIMPAC-2023-305
sudo docker-compose up -d
```

4. Go to the link http://localhost:8001/index.php/login to use the app

5. The guest account comes with a feature set and a dataset set that can be downloaded in the "Compute graph" page.

6. To add new users go to the link http://localhost:8000/ to manage the database. Login/password: admin/admin. In the `arg-db` databases, add new users to the table `users` by inserting email and md5(password).

## Contact

Contact me at lucasmrizzo@gmail.com

## References

The following papers have used this framework for experimentation.

<a id="1">[1]</a> 
**Rizzo, L.** and Longo, L. (2022) Examining the modelling capabilities of defeasible argumentation and non-monotonic fuzzy reasoning Information Fusion, 89, 537-566

<a id="2">[2]</a> 
Longo, L. and **Rizzo, L.** (2021) Examining the modelling capabilities of defeasible argumentation and non-monotonic fuzzy reasoning Knowledge-Based Systems, p. (in press)

<a id="3">[3]</a> 
**Rizzo, L.** (2020) Evaluating the Impact of Defeasible Argumentation as a Modelling Technique for Reasoning under Uncertainty. Doctoral Thesis, Technological University Dublin.

<a id="4">[4]</a>
**Rizzo, L.** and Longo, L. (2020) An empirical evaluation of the inferential capacity of defeasible argumentation, non-monotonic fuzzy reasoning and expert systems Expert Systems with Applications 147, p. (in press)

<a id="5">[5]</a>
**Rizzo, L.**, Dondio P., Longo L. (2020) Exploring the potential of defeasible argumentation for quantitative inferences in real-world contexts: An assessment of computational trust intelligence in: Proceedings for the 28th AIAI Irish Conference on Artificial Intelligence and Cognitive Science pp. 25-36

<a id="6">[6]</a>
**Rizzo, L.** and Longo, L. (2019) Inferential models of mental workload with defeasible argumentation and non-monotonic fuzzy reasoning: a comparative study in: 2nd Workshop on Advances In Argumentation In Artificial Intelligence pp. 11–26

<a id="7">[7]</a>
**Rizzo, L.** and Longo, L. (2018) A qualitative investigation of the degree of explainability of defeasible argumentation and non-monotonic fuzzy reasoning in: 26th AIAI Irish Conference on Artificial Intelligence and Cognitive Science pp. 138–149

<a id="8">[8]</a>
**Rizzo, L.**, Majnaric, L. and Longo, L. (2018) A comparative study of defeasible argumentation and non-monotonic fuzzy reasoning for elderly survival prediction using biomarkers in: AI*IA 2018 – Advances in Artificial Intelligence, (Eds.) C. Ghidini, B. Magnini, A. Passerini and P. Traverso pp. 197–209 Springer International Publishing, Cham

<a id="9">[9]</a>
**Rizzo, L.**, Majnaric, L., Dondio, P. and Longo, L. (2018) An investigation of argumentation theory for the prediction of survival in elderly using biomarkers in: Artificial Intelligence Applications and Innovations, (Eds.) L. Iliadis, I. Maglogiannis and V. Plagianakos pp. 385–397 Springer International Publishing, Cham

<a id="10">[10]</a>
**Rizzo, L.** and Longo, L. (2017) Representing and inferring mental workload via defeasible reasoning: a comparison with the nasa task load index and the workload profile in: 1st Workshop on Advances In Argumentation In Artificial Intelligence, Bari, Italy pp. 126–140
