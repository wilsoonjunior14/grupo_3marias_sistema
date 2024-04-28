describe('login screen test', () => {
  it('test login without type email and password', { baseUrl: "http://localhost:3000" }, () => {
    cy.login('', '');
  })

  it('test login with wrong email and wrong password', { baseUrl: "http://localhost:3000" }, () => {
    cy.login('12345@gmail.com', '12345');

    cy.contains('Nenhum registro foi encontrado.');
    cy.get('.alert-danger').should('exist');
  })

  it('test login with correct data', { baseUrl: "http://localhost:3000" }, () => {
    cy.login('wjunior_msn@hotmail.com', '12345');

    cy.get('.alert-danger').should('not.exist');
    cy.get('.navbar-brand').should('exist');
    cy.contains('Aniversariantes do Mês');
  })
})