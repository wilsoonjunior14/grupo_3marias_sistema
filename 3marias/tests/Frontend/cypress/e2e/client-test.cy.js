import {generateRandomLetters, generateRandomNumbers} from '../support/generators/generators';
import {gerarCpf} from '../support/generators/cpfgenerator';

describe('client screen test', () => {

  beforeEach(() => {
    cy.goToAddClients();
    cy.intercept({
      method: "POST",
      url: "http://localhost:5000/api/v1/clients"
    }).as("xhrClients");
  })

  it('positive test create client with required fields', () => {
    // Clients Form Screen
    cy.get('#nameInput').type(generateRandomLetters(20));
    cy.get('#cpfInput').type(gerarCpf());
    cy.get('[type="submit"]').click();
    cy.wait('@xhrClients').its('response.statusCode').should('equal', 201)

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('.alert-danger').should('not.exist');
  })

  it('negative test create client with required fields + short rg', () => {
      // Clients Form Screen
      cy.get('#nameInput').type(generateRandomLetters(20));
      cy.get('#cpfInput').type(gerarCpf());
      cy.get('#rgInput').type(generateRandomNumbers(3));
      cy.get('[type="submit"]').click();
      cy.wait('@xhrClients');

      // Clients Form Screen Asserts
      cy.get('#nameInput').invoke('val').then(value => expect(value).to.not.equal(''));
      cy.get('#cpfInput').invoke('val').then(value => expect(value).to.not.equal(''));
      cy.get('#rgInput').invoke('val').then(value => expect(value).to.not.equal(''));
      cy.get('.alert-danger').should('exist');
    })

  it('negative test create client with required fields + rg + short rg_organ', () => {
    // Clients Form Screen
    cy.get('#nameInput').type(generateRandomLetters(20));
    cy.get('#cpfInput').type(gerarCpf());
    cy.get('#rgInput').type(generateRandomNumbers(13));
    cy.get('#rg_organInput').type(generateRandomLetters(2));
    cy.get('[type="submit"]').click();
    cy.wait('@xhrClients');

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#rgInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#rg_organInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
  })

  it('negative test create client with required fields + rg + short rg_date', () => {
    // Clients Form Screen
    cy.get('#nameInput').type(generateRandomLetters(20));
    cy.get('#cpfInput').type(gerarCpf());
    cy.get('#rgInput').type(generateRandomNumbers(13));
    cy.get('#rg_dateInput').type(generateRandomNumbers(2));
    cy.get('[type="submit"]').click();
    cy.wait('@xhrClients');

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#rgInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#rg_dateInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
  })
})