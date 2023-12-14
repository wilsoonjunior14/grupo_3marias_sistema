import Button from "react-bootstrap/esm/Button";

export default function BackButton() {
    return (
        <Button key={"bntBack"}
            variant={"success"}
            onClick={() => window.history.go(-1)}>
                <i className="material-icons float-left">keyboard_return</i>
            Voltar
        </Button>
    );
}