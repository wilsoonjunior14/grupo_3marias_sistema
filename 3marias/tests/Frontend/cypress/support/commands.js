// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add('login', (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add('drag', { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add('dismiss', { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Cypress.Commands.overwrite('visit', (originalFn, url, options) => { ... })
Cypress.Commands.add('login', (email, password) => {
    cy.intercept({
        method: "POST",
        url: Cypress.env().baseUrl + "/api/login"
    }).as("xhrLogin");

    cy.visit(Cypress.env().baseUrl);
    if (email.length > 0 && password.length > 0) {
        cy.get('#emailInput').type(email);
        cy.get('#passwordInput').type(password);
        cy.get('.custom-btn').click();
        cy.wait('@xhrLogin');
    }
})

Cypress.Commands.add('doLogin', () => {
    cy.intercept(Cypress.env().baseUrl + "/api/login").as("xhrLogin");

    cy.visit(Cypress.env().baseUrl);
    cy.get('#emailInput').type("wjunior_msn@hotmail.com");
    cy.get('#passwordInput').type("12345");
    cy.get('.custom-btn').click();

    cy.wait('@xhrLogin').its('response.statusCode').should('equal', 200)
})

Cypress.Commands.add('goToAddClients', () => {
    cy.doLogin();
    // Clients List Screen
    cy.visit(Cypress.env().baseUrl + "/admin/clients");
    cy.get('[data-tooltip-id="btnAdd"]').should('exist');
    cy.get('[data-tooltip-id="btnAdd"]').click();    
})