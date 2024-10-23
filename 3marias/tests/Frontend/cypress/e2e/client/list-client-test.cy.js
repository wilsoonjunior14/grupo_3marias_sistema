import {generateRandomLetters, generateRandomNumbers} from '../../support/generators/generators';
import {gerarCpf} from '../../support/generators/cpfgenerator';

describe('client screen test', () => {

  beforeEach(() => {
  })

  // it('positive test create client with required fields and check client within clients list', () => {
  //   cy.goToAddClients();
  //   cy.intercept({
  //     method: "POST",
  //     url: Cypress.env().baseUrl + "/api/v1/clients"
  //   }).as("xhrClients");

  //   let clientName = generateRandomLetters(20);
  //   cy.get('#nameInput').type(clientName);
  //   cy.get('#cpfInput').type(gerarCpf());

  //   // Clients Form Screen
  //   cy.get('[type="submit"]').click();
  //   cy.wait('@xhrClients').its('response.statusCode').should('equal', 201)

  //   // Clients Form Screen Asserts
  //   cy.get('#nameInput').invoke('val').then(value => expect(value).to.equal(''));
  //   cy.get('#cpfInput').invoke('val').then(value => expect(value).to.equal(''));
  //   cy.get('.alert-danger').should('not.exist');

  //   // Clients List Screen
  //   // // Turn Back to Clients List Screen
  //   cy.get('[type="button"]').should('exist');
  //   cy.get('[type="button"]').click();
  //   cy.wait(20000);

  //   // // Searching by client
  //   cy.get('.accordion-button').click();
  //   cy.get('#nameInput').type(clientName);
  //   cy.get('[class="btn btn-success btn-lg"]').click();

  //   // // Identifying on table
  //   cy.get('td').contains(clientName);
  // })

  // it('positive test create client with required fields and check client name and email within clients list', () => {
  //   cy.goToAddClients();
  //   cy.intercept({
  //     method: "POST",
  //     url: Cypress.env().baseUrl + "/api/v1/clients"
  //   }).as("xhrClients");

  //   let clientName = generateRandomLetters(20);
  //   let clientEmail = generateRandomLetters(5) + "@gmail.com";
  //   cy.get('#nameInput').type(clientName);
  //   cy.get('#cpfInput').type(gerarCpf());
  //   cy.get('#emailInput').type(clientEmail);

  //   // Clients Form Screen
  //   cy.get('[type="submit"]').click();
  //   cy.wait('@xhrClients').its('response.statusCode').should('equal', 201)

  //   // Clients Form Screen Asserts
  //   cy.get('#nameInput').invoke('val').then(value => expect(value).to.equal(''));
  //   cy.get('#cpfInput').invoke('val').then(value => expect(value).to.equal(''));
  //   cy.get('#emailInput').invoke('val').then(value => expect(value).to.equal(''));
  //   cy.get('.alert-danger').should('not.exist');

  //   // Clients List Screen
  //   // // Turn Back to Clients List Screen
  //   cy.get('[type="button"]').should('exist');
  //   cy.get('[type="button"]').click();
  //   // cy.wait(20000);
  //   cy.intercept(Cypress.env().baseUrl + "/api/v1/clients").as("xhrClients");
  //   //cy.visit(Cypress.env().baseUrl + "/admin/clients");
  //   cy.wait('@xhrClients');

  //   // // Searching by client
  //   cy.get('.accordion-button').click();
  //   cy.get('#nameInput').type(clientName);
  //   cy.get('#emailInput').type(clientEmail);
  //   cy.get('[class="btn btn-success btn-lg"]').click();

  //   // // Identifying on table
  //   cy.get('td').contains(clientName);
  //   cy.get('td').contains(clientEmail);
  // })

})