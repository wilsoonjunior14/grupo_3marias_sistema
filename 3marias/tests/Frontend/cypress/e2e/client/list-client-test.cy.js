import {generateRandomLetters, generateRandomNumbers} from '../../support/generators/generators';
import {gerarCpf} from '../../support/generators/cpfgenerator';

describe('client screen test', () => {

  beforeEach(() => {
  })

  it('positive test create client with required fields and check client within clients list', () => {
    cy.goToAddClients();
    cy.intercept({
      method: "POST",
      url: Cypress.env().baseUrl + "/api/v1/clients"
    }).as("xhrClients");

    let clientName = generateRandomLetters(20);
    cy.get('#nameInput').type(clientName);
    cy.get('#cpfInput').type(gerarCpf());

    // Clients Form Screen
    cy.get('[type="submit"]').click();
    cy.wait('@xhrClients').its('response.statusCode').should('equal', 201)

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('.alert-danger').should('not.exist');

    // Clients List Screen
    cy.get('[type="button"]').should('exist');
    cy.get('[type="button"]').click();
    cy.wait(15000);

    cy.get('.accordion-button').click();
    cy.get('#nameInput').type(clientName);
    cy.get('[type="button"],[class="btn btn-success btn-lg"]').should('exist');
    cy.get('[type="button"],[class="btn btn-success btn-lg"]').click();
  })

})