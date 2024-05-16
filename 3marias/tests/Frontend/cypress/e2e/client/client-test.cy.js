import {generateRandomLetters, generateRandomNumbers} from '../../support/generators/generators';
import {gerarCpf} from '../../support/generators/cpfgenerator';

describe('client screen test', () => {

  beforeEach(() => {
    cy.goToAddClients();
    cy.intercept({
      method: "POST",
      url: Cypress.env().baseUrl + "/api/v1/clients"
    }).as("xhrClients");

    cy.get('#nameInput').type(generateRandomLetters(20));
    cy.get('#cpfInput').type(gerarCpf());
  })

  it('negative test create client with required fields + short rg', () => {
      // Clients Form Screen
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
    cy.get('#rgInput').type(generateRandomNumbers(13));
    cy.get('#rg_organInput').type(generateRandomLetters(2));
    cy.get('[type="submit"]').click();
    cy.wait(3000);

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#rgInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#rg_organInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
  })

  it('negative test create client with required fields + rg + short rg_date', () => {
    // Clients Form Screen
    cy.get('#rgInput').type(generateRandomNumbers(13));
    cy.get('#rg_dateInput').type(generateRandomNumbers(2));
    cy.get('[type="submit"]').click();
    cy.wait(3000);

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#rgInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#rg_dateInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
  })

  it('negative test create client with required fields + middle rg', () => {
    // Clients Form Screen
    cy.get('#rgInput').type(generateRandomNumbers(5));
    cy.get('[type="submit"]').click();
    cy.wait('@xhrClients');

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#rgInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
    cy.contains('Campo RG do Cliente está inválido.');
  })

  it('negative test create client with required fields + invalid rg_organ', () => {
    // Clients Form Screen
    cy.get('#rg_organInput').type(generateRandomNumbers(10));
    cy.get('[type="submit"]').click();
    cy.wait(3000);

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#rg_organInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
    cy.contains('Òrgão do RG do Cliente não é válido.');
  })

  it('negative test create client with required fields + wrong rg_date', () => {
    // Clients Form Screen
    cy.get('#rg_dateInput').type("00/00/0000");
    cy.get('[type="submit"]').click();
    cy.wait(3000);

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#rg_dateInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
    cy.contains('Campo de Data de Emissão do RG do Cliente é inválido');
  })

  it('negative test create client with required fields + short ocupation', () => {
    // Clients Form Screen
    cy.get('#ocupationInput').type(generateRandomLetters(2));
    cy.get('[type="submit"]').click();
    cy.wait(3000);

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#ocupationInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
  })

  it('negative test create client with required fields + invalid email', () => {
    // Clients Form Screen
    cy.get('#emailInput').type(generateRandomLetters(10) + "@@@@@.com");
    cy.get('[type="submit"]').click();

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#emailInput').invoke('val').then(value => expect(value).to.not.equal(''));
  })

  it('negative test create client with required fields + wrong phone', () => {
    // Clients Form Screen
    cy.get('#phoneInput').type(generateRandomNumbers(2));
    cy.get('[type="submit"]').click();
    cy.wait(3000);

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#phoneInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
  })

  it('negative test create client with required fields + wrong type ocupation', () => {
    // Clients Form Screen
    cy.get('#ocupationInput').type(generateRandomNumbers(10));
    cy.get('[type="submit"]').click();
    cy.wait(3000);

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#ocupationInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
  })


  it('negative test create client with required fields + CASADO state + name_dependent + cpf_dependent using it equals cpf', () => {
    // Clients Form Screen
    let cpf = gerarCpf();
    cy.get('#stateInput').select('Casado');
    cy.wait(1500);
    cy.get('#cpfInput').clear();
    cy.get('#cpfInput').type(cpf);
    cy.get('#name_dependentInput').type(generateRandomLetters(50));
    cy.get('#cpf_dependentInput').type(cpf);
    cy.get('[type="submit"]').click();
    cy.wait('@xhrClients');

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.equal(cpf));
    cy.get('#cpf_dependentInput').invoke('val').then(value => expect(value).to.equal(cpf));
    cy.get('#stateInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
    cy.contains('Campo CPF do Cônjugue deve ser diferente do CPF do Cliente.');
  })

  it('negative test create client with required fields + wrong naturality', () => {
    // Clients Form Screen
    cy.get('#naturalityInput').type(generateRandomNumbers(10));
    cy.get('[type="submit"]').click();

    // Clients Form Screen Asserts
    cy.get('#naturalityInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
    cy.contains('Naturalidade do Cliente não é válido.');
  })

  it('negative test create client with required fields + short naturality', () => {
    // Clients Form Screen
    cy.get('#naturalityInput').type(generateRandomNumbers(2));
    cy.get('[type="submit"]').click();

    // Clients Form Screen Asserts
    cy.get('#naturalityInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
    cy.contains('Naturalidade do Cliente deve conter entre 3 e 255 caracteres.');
  })

  it('negative test create client with required fields + white spaces naturality', () => {
    // Clients Form Screen
    cy.get('#naturalityInput').type("                   ");
    cy.get('[type="submit"]').click();

    // Clients Form Screen Asserts
    cy.get('#naturalityInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
    cy.contains('Campo Naturalidade do Cliente deve conter no mínimo 3 caracteres.');
  })

  it('negative test create client with required fields + wrong nationality', () => {
    // Clients Form Screen
    cy.get('#nationalityInput').type(generateRandomNumbers(10));
    cy.get('[type="submit"]').click();

    // Clients Form Screen Asserts
    cy.get('#nationalityInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
    cy.contains('Nacionalidade do Cliente não é válido.');
  })

  it('negative test create client with required fields + short nationality', () => {
    // Clients Form Screen
    cy.get('#nationalityInput').type(generateRandomNumbers(2));
    cy.get('[type="submit"]').click();

    // Clients Form Screen Asserts
    cy.get('#nationalityInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
    cy.contains('Nacionalidade do Cliente deve conter entre 3 e 255 caracteres.');
  })

  it('negative test create client with required fields + white spaces nationality', () => {
    // Clients Form Screen
    cy.get('#nationalityInput').type("                   ");
    cy.get('[type="submit"]').click();

    // Clients Form Screen Asserts
    cy.get('#nationalityInput').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
    cy.contains('Campo Nacionalidade do Cliente deve conter no mínimo 3 caracteres.');
  })

  it('negative test create client with required fields + zero salary', () => {
    // Clients Form Screen
    cy.get('#salary').type("0");
    cy.get('[type="submit"]').click();

    // Clients Form Screen Asserts
    cy.get('#salary').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
    cy.contains('Renda Bruta do Cliente não é válido. Tente utilizar o padrão XXX.XXX,XX');
  })

  it('negative test create client with required fields + wrong salary', () => {
    // Clients Form Screen
    cy.get('#salary').type(generateRandomLetters(10));
    cy.get('[type="submit"]').click();

    // Clients Form Screen Asserts
    cy.get('#salary').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('.alert-danger').should('exist');
    cy.contains('Renda Bruta do Cliente não é válido. Tente utilizar o padrão XXX.XXX,XX');
  })

  it('negative test create client with required fields + invalid pattern salary', () => {
    // Clients Form Screen
    cy.get('#salary').type(generateRandomNumbers(5) + "," + generateRandomNumbers(2) + "," + generateRandomNumbers(2) + ".");
    cy.get('[type="submit"]').click();

    // Clients Form Screen Asserts
    cy.get('#salary').invoke('val').then(value => expect(value).to.not.equal(''));
    cy.get('.alert-danger').should('exist');
    cy.contains('Renda Bruta do Cliente não é válido. Tente utilizar o padrão XXX.XXX,XX');
  })

  it('positive test create client with required fields + long ocupation', () => {
    // Clients Form Screen
    cy.get('#ocupationInput').type(generateRandomLetters(500));
    cy.get('[type="submit"]').click();
    cy.wait('@xhrClients');

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#ocupationInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('.alert-danger').should('not.exist');
  })

  it('positive test create client with required fields + Female gender sex', () => {
    // Clients Form Screen
    cy.get('#sexInput').select('Feminino');
    cy.get('[type="submit"]').click();
    cy.wait('@xhrClients');

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#sexInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('.alert-danger').should('not.exist');
  })

  it('positive test create client with required fields + Male gender sex', () => {
    // Clients Form Screen
    cy.get('#sexInput').select('Masculino');
    cy.get('[type="submit"]').click();
    cy.wait('@xhrClients');

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#sexInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('.alert-danger').should('not.exist');
  })

  it('positive test create client with required fields + long rg', () => {
    // Clients Form Screen
    cy.get('#rgInput').type(generateRandomNumbers(1000));
    cy.get('[type="submit"]').click();
    cy.wait('@xhrClients');

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#rgInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('.alert-danger').should('not.exist');
  })

  it('positive test create client with required fields + email', () => {
    // Clients Form Screen
    cy.get('#emailInput').type(generateRandomLetters(10) + "@" + generateRandomLetters(5) + ".com");
    cy.get('[type="submit"]').click();
    cy.wait('@xhrClients');

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#emailInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('.alert-danger').should('not.exist');
  })

  it('positive test create client with required fields + email, phone, naturality, nationality and salary', () => {
    // Clients Form Screen
    cy.get('#emailInput').type(generateRandomLetters(10) + "@" + generateRandomLetters(5) + ".com");
    cy.get('#phoneInput').type("(00)00000-0000");
    cy.get('#naturalityInput').type(generateRandomLetters(10));
    cy.get('#nationalityInput').type(generateRandomLetters(10));
    cy.get('#salary').type("1500,00");
    cy.get('[type="submit"]').click();
    cy.wait('@xhrClients');

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#emailInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#phoneInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#naturalityInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#nationalityInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#salary').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('.alert-danger').should('not.exist');
  })

  it('positive test create client with required fields', () => {
    // Clients Form Screen
    cy.get('[type="submit"]').click();
    cy.wait('@xhrClients').its('response.statusCode').should('equal', 201)

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('.alert-danger').should('not.exist');
  })

  it('positive test create client with required fields + MALE sex + CASADO state + name_dependent + cpf_dependent', () => {
    // Clients Form Screen
    cy.get('#sexInput').select('Masculino');
    cy.get('#stateInput').select('Casado');
    cy.wait(1500);
    cy.get('#name_dependentInput').type(generateRandomLetters(50));
    cy.get('#cpf_dependentInput').type(gerarCpf());
    cy.get('[type="submit"]').click();
    cy.wait('@xhrClients');

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#stateInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#sexInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('.alert-danger').should('not.exist');
    cy.get('#name_dependentInput').should('not.exist');
    cy.get('#cpf_dependentInput').should('not.exist');
  })

  it('positive test create client with required fields + FEMALE sex + Solteiro state + 1 or More Buyers + name_dependent + cpf_dependent + rg_dependent', () => {
    // Clients Form Screen
    cy.get('#sexInput').select('Feminino');
    cy.get('#stateInput').select('Solteiro');
    cy.get('#has_many_buyersInput').select('Sim');
    cy.wait(1500);
    cy.get('#name_dependentInput').type(generateRandomLetters(50));
    cy.get('#cpf_dependentInput').type(gerarCpf());
    cy.get('#rg_dependentInput').type(generateRandomNumbers(14));
    cy.get('[type="submit"]').click();
    cy.wait('@xhrClients');

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#stateInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#sexInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#has_many_buyersInput').invoke('val').then(value => expect(value).to.not.equal('Sim'));
    cy.get('.alert-danger').should('not.exist');
    cy.get('#name_dependentInput').should('not.exist');
    cy.get('#cpf_dependentInput').should('not.exist');
    cy.get('#rg_dependentInput').should('not.exist');
  })

  it('positive test create client with required fields + FEMALE sex + Solteiro state + No More than 1 Buyers', () => {
    // Clients Form Screen
    cy.get('#sexInput').select('Feminino');
    cy.get('#stateInput').select('Solteiro');
    cy.get('#has_many_buyersInput').select('Não');
    cy.get('[type="submit"]').click();
    cy.wait('@xhrClients');

    // Clients Form Screen Asserts
    cy.get('#nameInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#cpfInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#stateInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#sexInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('.alert-danger').should('not.exist');
    cy.get('#cpf_dependentInput').should('not.exist');
    cy.get('#rg_dependentInput').should('not.exist');
  })

  it('positive test create client with required fields + address fields', () => {
    cy.wait(3000);

    // Clients Form Screen
    cy.get('#city_idInput').select('IBIAPINA');
    cy.get('#zipcodeInput').type('62360-000');
    cy.get('#addressInput').type(generateRandomLetters(10));
    cy.get('#neighborhoodInput').type(generateRandomLetters(10));
    cy.get('#complementInput').type(generateRandomLetters(10));
    cy.get('#numberInput').type(generateRandomNumbers(3));
    cy.get('[type="submit"]').click();
    cy.wait('@xhrClients');

    // Clients Form Screen Asserts
    cy.wait(3000);
    cy.get('#city_idInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#zipcodeInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#addressInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#neighborhoodInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#complementInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('#numberInput').invoke('val').then(value => expect(value).to.equal(''));
    cy.get('.alert-danger').should('not.exist');
  })
})